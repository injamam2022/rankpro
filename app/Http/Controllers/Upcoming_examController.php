<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Models\Exam;
use App\Models\Exam_user;
use App\Models\Subject;
use App\Models\Exam_status;
use App\Models\Question_paper_subject;
use App\Models\Offline_exam_question;

use DB;

class Upcoming_examController extends Controller
{
    private function getLanguageId(){
        return 1;
    }

    /**
     * Portal exam base query — no question joins (those inflate paginate counts).
     */
    private function portalExamBaseQuery($subjectId = null, $examType = null, $search = '')
    {
        return Exam::assignedTo(Auth::id())
            ->where('exams.is_deleted', 0)
            ->where('exams.status', 1)
            ->where(function ($q) {
                $q->whereNull('exams.exam_code')->orWhere('exams.exam_code', 'not like', 'CT-%');
            })
            ->when(filled($examType), function ($query) use ($examType) {
                $query->where('exams.type', $examType);
            })
            ->when(filled($subjectId), function ($query) use ($subjectId) {
                $query->where(function ($outer) use ($subjectId) {
                    $outer->whereExists(function ($sub) use ($subjectId) {
                        $sub->select(DB::raw(1))
                            ->from('question_paper_questions')
                            ->join('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                            ->whereColumn('question_paper_questions.question_paper_id', 'exams.question_paper_id')
                            ->where('questions.subject_id', $subjectId);
                    })->orWhereExists(function ($sub) use ($subjectId) {
                        $sub->select(DB::raw(1))
                            ->from('offline_exam_questions')
                            ->whereColumn('offline_exam_questions.exam_id', 'exams.id')
                            ->where('offline_exam_questions.subject_id', $subjectId);
                    });
                });
            })
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search) . '%';
                $query->where(function ($q) use ($like) {
                    $q->where('exams.name', 'like', $like)
                      ->orWhere('exams.exam_code', 'like', $like);
                });
            })
            ->listedForStudentPortal();
    }

    private function enrichPortalExamRow($value, $attemptedByExam): void
    {
        $value->subject_names = '';
        if ($value->question_paper_id) {
            $subject_list = Question_paper_subject::select(['subjects.name'])
                ->leftJoin('subjects', 'subjects.id', '=', 'question_paper_subjects.subject_id')
                ->where('question_paper_subjects.question_paper_id', $value->question_paper_id)
                ->groupBy('question_paper_subjects.subject_id', 'subjects.name')
                ->get();
            $value->subject_names = $subject_list->pluck('name')->filter()->unique()->values()->implode(', ');
        } else {
            $subject_list = Offline_exam_question::select(['subjects.name'])
                ->leftJoin('subjects', 'subjects.id', '=', 'offline_exam_questions.subject_id')
                ->where('offline_exam_questions.exam_id', $value->id)
                ->groupBy('offline_exam_questions.subject_id')
                ->get();
            $value->subject_names = $subject_list->pluck('name')->filter()->unique()->values()->implode(', ');
        }

        if ($attemptedByExam->has($value->id)) {
            $row = $attemptedByExam->get($value->id);
            $value->portal_status = 'attempted';
            $value->attempt_score = $row->total_number;
            $value->attempt_max = $row->total_mark;
        } else {
            $value->portal_status = (method_exists($value, 'canBeStarted') && $value->canBeStarted())
                ? 'available'
                : 'scheduled';
        }
        $value->difficulty_label = !empty($value->is_proctored) ? 'Advanced Challenger' : 'NTA Standard';
        $value->series_label = ((int) ($value->type ?? 0) === 2)
            ? 'RANKPRO OFFLINE OMR SERIES'
            : 'RANKPRO NEET ELITE SERIES 2027';
    }

    public function upcoming_exam(Request $request){
        $language_id = $this->getLanguageId();
        $data = [];
        $data['user'] = Auth::user();
        $data['exam_type'] = $request->exam_type;
        $data['subject_id'] = $request->subject_id;
        $data['q'] = trim((string) $request->get('q', ''));
        $data['subject_list'] = Subject::where('status',1)->get();

        $subject_id = $request->subject_id;
        $exam_type = $request->exam_type;
        $search = $data['q'];

        $attempted = Exam_user::query()
            ->select([
                'exam_users.exam_id',
                'exam_users.total_number',
                'exam_users.total_mark',
                'exam_users.percentage',
            ])
            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
            ->where('exam_users.user_id', Auth::id())
            ->where('exams.is_deleted', 0)
            ->where(function ($q) {
                $q->whereNull('exams.exam_code')->orWhere('exams.exam_code', 'not like', 'CT-%');
            })
            ->whereNotNull('exam_users.percentage')
            ->where('exam_users.percentage', '!=', '')
            ->orderByDesc('exam_users.id')
            ->get();
        $attemptedByExam = $attempted->keyBy('exam_id');

        // Metrics + schedule: lightweight columns only, no question-row joins.
        $scheduleExams = $this->portalExamBaseQuery(null, $exam_type, '')
            ->select([
                'exams.id',
                'exams.name',
                'exams.type',
                'exams.exam_date',
                'exams.exam_time',
                'exams.exam_end_date',
                'exams.exam_end_time',
                'exams.is_ended',
                'exams.is_proctored',
                'question_papers.total_time_for_exam',
            ])
            ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
            ->orderBy('exams.exam_date', 'ASC')
            ->orderBy('exams.id', 'ASC')
            ->limit(100)
            ->get();

        foreach ($scheduleExams as $value) {
            if ($attemptedByExam->has($value->id)) {
                $value->portal_status = 'attempted';
            } else {
                $value->portal_status = (method_exists($value, 'canBeStarted') && $value->canBeStarted())
                    ? 'available'
                    : 'scheduled';
            }
        }

        $data['schedule_exam_list'] = $scheduleExams;
        $data['available_count'] = $scheduleExams->where('portal_status', 'available')->count();
        $data['scheduled_count'] = $scheduleExams->where('portal_status', 'scheduled')->count();
        $data['attempted_count'] = $attemptedByExam->count();
        $data['highest_score'] = (int) ($attempted->max('total_number') ?? 0);
        $topAttempt = $attempted->sortByDesc('total_number')->first();
        $data['highest_max'] = (int) ($topAttempt->total_mark ?? 720);
        $nextScheduled = $scheduleExams->where('portal_status', 'scheduled')->first();
        $data['next_slot_label'] = $nextScheduled
            ? (\Carbon\Carbon::parse($nextScheduled->exam_date.' '.($nextScheduled->exam_time ?: '00:00:00'))->format('D g:i A').' Slot')
            : 'No upcoming slot';

        // 1) Paginate distinct exam IDs only (accurate total, 8 per page).
        $idPaginator = $this->portalExamBaseQuery($subject_id, $exam_type, $search)
            ->select('exams.id')
            ->orderBy('exams.exam_date', 'ASC')
            ->orderBy('exams.id', 'ASC')
            ->paginate(8)
            ->appends($request->query());

        $pageIds = $idPaginator->getCollection()->pluck('id')->filter()->values()->all();

        // 2) Load full tile data for this page only.
        $pageExams = collect();
        if (!empty($pageIds)) {
            $pageExams = Exam::query()
                ->select([
                    'exams.*',
                    'locations.location_name',
                    'question_papers.no_of_question',
                    'question_papers.totals_marks_for_exam',
                    'question_papers.total_time_for_exam',
                    'exam_statuses.type as exam_status',
                ])
                ->leftJoin('locations', 'exams.location_id', '=', 'locations.id')
                ->leftJoin('question_papers', 'exams.question_paper_id', '=', 'question_papers.id')
                ->leftJoin('exam_statuses', function ($join) {
                    $join->on('exams.id', '=', 'exam_statuses.exam_id')
                         ->where('exam_statuses.user_id', Auth::id());
                })
                ->whereIn('exams.id', $pageIds)
                ->get()
                ->keyBy('id');

            $ordered = collect();
            foreach ($pageIds as $id) {
                if ($pageExams->has($id)) {
                    $row = $pageExams->get($id);
                    $this->enrichPortalExamRow($row, $attemptedByExam);
                    $ordered->push($row);
                }
            }
            $pageExams = $ordered;
        }

        $idPaginator->setCollection($pageExams);
        $data['upcoming_exam_list'] = $idPaginator;

        return view('site.upcoming_exam',$data);
    }

    public function upcoming_exam_accept(Request $request){
        $loginCheck = Exam::assignedTo(Auth::id())->where('status', 1)->where('is_deleted', 0)->where('id',$request->id)->firstOrFail();

        $user_id = Auth::user()->id;

        if($loginCheck){
            
            $exam_status = Exam_status::where('exam_id',$loginCheck->id)->where('user_id',$user_id)->first();

            if(!$exam_status){
                $insertData = [];
                $insertData['user_id'] = $user_id;
                $insertData['type'] = 1;
                $insertData['exam_id'] = $loginCheck->id;

                Exam_status::create($insertData);

                toastr()->success('Exam accepted successfully.');
            }else{
                toastr()->success('Exam already accepted.');
            }
        }

        
        return redirect()->route('upcoming_exam');
    }

    public function upcoming_exam_reject(Request $request){
        $loginCheck = Exam::assignedTo(Auth::id())->where('status', 1)->where('is_deleted', 0)->where('id',$request->id)->firstOrFail();

        $user_id = Auth::user()->id;

        if($loginCheck){
            
            $exam_status = Exam_status::where('exam_id',$loginCheck->id)->where('user_id',$user_id)->first();

            if(!$exam_status){
                $insertData = [];
                $insertData['user_id'] = $user_id;
                $insertData['type'] = 0;
                $insertData['exam_id'] = $loginCheck->id;

                Exam_status::create($insertData);

                toastr()->success('Exam accepted successfully.');
            }else{
                toastr()->success('Exam already accepted.');
            }
        }

        
        return redirect()->route('upcoming_exam');
    }


    
}
