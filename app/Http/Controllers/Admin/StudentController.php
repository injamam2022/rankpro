<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use App\Models\User;
use App\Models\Exam;
use App\Models\Exam_user;
use App\Models\Exam_result;
use App\Models\Question_detail;
use App\Models\Question_paper_question;
use App\Models\Offline_exam_question;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use DB;

class StudentController extends Controller
{
    public function list(){
        $data = [];
        $data['list'] = User::get();
        // dd($data['list']);
        return view('admin.student.list',$data);
    }


    public function add(){
        $data = [];
        return view('admin.student.add',$data);
    }

    public function save(Request $request){
        $validatedData = $request->validate([
                            'name' => 'required'
                        ]);

        $input = $request->all();

        $loginCheck = User::where('email',$request->email)->first();

        if (!$loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_img')){
                $insertData['profile_img'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/profileImage/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$insertData['profile_img']);


                $destinationPath = public_path('/uploads/profileImage');
                $image->move($destinationPath, $insertData['profile_img']);
            }

            do {
                $rankproId = random_int(100000, 999999);
            } while (User::where('rankpro_id', $rankproId)->exists());

            $insertData['first_name'] = $request->first_name;
            $insertData['last_name'] = $request->last_name;
            $insertData['address'] = $request->address;
            $insertData['mobile_number'] = $request->mobile_number;
            $insertData['is_whatsapp'] = $request->is_whatsapp;
            $insertData['email_id'] = $request->email_id;
            $insertData['father_full_name'] = $request->father_full_name;
            $insertData['father_occupation'] = $request->father_occupation;
            $insertData['father_mobile_number'] = $request->father_mobile_number;
            $insertData['father_qualification'] = $request->father_qualification;
            $insertData['mother_full_name'] = $request->mother_full_name;
            $insertData['mother_occupation'] = $request->mother_occupation;
            $insertData['mother_mobile_number'] = $request->mother_mobile_number;
            $insertData['mother_qualification'] = $request->mother_qualification;
            $insertData['qualification_details'] = $request->qualification_details;
            $insertData['access_token'] = md5(time().$request->email);
            $insertData['hash_code'] = md5(time().$request->email);
            $insertData['password'] = md5($request->password);
            $insertData['status'] = $request->status;
            $insertData['rankpro_id'] = $rankproId;

            $user = User::create($insertData);


            toastr()->success('Student added successfully.');
            return redirect()->route('admin.student.add');
        }else{
            toastr()->warning('Student already exist');
            return back()->withInput();
        }
    }

    public function edit(Request $request){
        $input = $request->all();
        $data = [];
        $data['details'] = User::where('id',$request->id)->first();
        return view('admin.student.edit',$data);
    }

    public function update(Request $request){
        $validatedData = $request->validate([
                            'first_name' => 'required'
                        ]);

        $input = $request->all();
        $loginCheck = User::where('id',$request->id)->first();

        if ($loginCheck){
            $insertData = [];
            if ($image = $request->file('profile_img')){
                $insertData['profile_img'] = time().'.'.$image->getClientOriginalExtension();


                $destinationPath = public_path('/uploads/profileImage');
                $image->move($destinationPath, $insertData['profile_img']);

                if($loginCheck->profile_img){
                    if (file_exists(public_path($loginCheck->profile_img))) {
                        unlink(public_path($loginCheck->profile_img));
                    }
                }
                $insertData['profile_img'] = $insertData['profile_img'];
            }
            if ($image = $request->file('certificate')){
                $insertData['certificate'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/certificate');
                $image->move($destinationPath, $insertData['certificate']);

                if($loginCheck->certificate){
                    if (file_exists(public_path($loginCheck->certificate))) {
                        unlink(public_path($loginCheck->certificate));
                    }
                }
                $insertData['certificate'] = 'uploads/certificate/'.$insertData['certificate'];
            }
            if ($image = $request->file('guardian_signature')){
                $insertData['guardian_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/guardian_signature');
                $image->move($destinationPath, $insertData['guardian_signature']);

                if($loginCheck->guardian_signature){
                    if (file_exists(public_path($loginCheck->guardian_signature))) {
                        unlink(public_path($loginCheck->guardian_signature));
                    }
                }
                $insertData['guardian_signature'] = 'uploads/guardian_signature/'.$insertData['guardian_signature'];
            }
            if ($image = $request->file('student_signature')){
                $insertData['student_signature'] = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/uploads/student_signature');
                $image->move($destinationPath, $insertData['student_signature']);

                if($loginCheck->student_signature){
                    if (file_exists(public_path($loginCheck->student_signature))) {
                        unlink(public_path($loginCheck->student_signature));
                    }
                }
                $insertData['student_signature'] = 'uploads/student_signature/'.$insertData['student_signature'];
            }

            $insertData['first_name'] = $request->first_name;
            $insertData['last_name'] = $request->last_name;
            $insertData['address'] = $request->address;
            $insertData['mobile_number'] = $request->mobile_number;
            $insertData['is_whatsapp'] = ($request->is_whatsapp)?1:0;
            $insertData['email_id'] = $request->email_id;
            $insertData['father_full_name'] = $request->father_full_name;
            $insertData['father_occupation'] = $request->father_occupation;
            $insertData['father_mobile_number'] = $request->father_mobile_number;
            $insertData['father_qualification'] = $request->father_qualification;
            $insertData['mother_full_name'] = $request->mother_full_name;
            $insertData['mother_occupation'] = $request->mother_occupation;
            $insertData['mother_mobile_number'] = $request->mother_mobile_number;
            $insertData['mother_qualification'] = $request->mother_qualification;
            $insertData['qualification_details'] = $request->qualification_details;

            $insertData['address'] = $request->address;
            if($request->password){
                $insertData['password'] = md5($request->password);
            }

            $insertData['status'] = $request->status;

            $loginCheck->update($insertData);
            toastr()->success('Student updated successfully.');
            return redirect()->route('admin.student');
        }else{
            toastr()->warning('Student already exist');
            return back()->withInput();
        }
    }

    public function delete(Request $request){
        $id = $request->id;
        User::where('id',$id)->delete();
        toastr()->success('Student deleted successfully.');
        return redirect()->route('admin.student');
    }

    public function change(Request $request){
        $id = $request->id;

        $loginCheck = User::where('id',$request->id)->first();

        if($loginCheck){
            if($loginCheck->status){
                $loginCheck->update(["status"=>0]);
            }else{
                $loginCheck->update(["status"=>1]);
            }
        }

        toastr()->success('Student status change successfully.');
        return redirect()->route('admin.student');
    }

    public function exam_list(Request $request)
    {
        $user = User::findOrFail($request->id);
        $userId = (int) $user->id;

        $list = Exam_user::select([
                'exam_users.*',
                'exams.name',
                'exams.exam_code',
                'exams.type',
                'exams.exam_date',
                'exams.total_time_for_exam',
            ])
            ->leftJoin('exams', 'exam_users.exam_id', '=', 'exams.id')
            ->where('exam_users.user_id', $userId)
            ->where('exams.is_deleted', 0)
            ->orderByDesc('exam_users.id')
            ->get();

        $rankRow = DB::select("
            SELECT COUNT(*) + 1 AS rank
            FROM (
                SELECT exam_users.user_id, SUM(exam_users.total_number) as total_number
                FROM exam_users
                LEFT JOIN exams ON exams.id = exam_users.exam_id
                WHERE exams.is_deleted = 0
                GROUP BY exam_users.user_id
            ) as totals
            WHERE total_number > (
                SELECT COALESCE(SUM(exam_users.total_number), 0)
                FROM exam_users
                LEFT JOIN exams ON exams.id = exam_users.exam_id
                WHERE exams.is_deleted = 0
                AND exam_users.user_id = ?
            )
        ", [$userId]);

        $youOverall = $this->studentResultStats($userId, null);
        $topperOverall = $this->topperResultStats(null);
        $averageOverall = $this->averageResultStats(null);
        $rank = (int) ($rankRow[0]->rank ?? 1);
        $cohort = (int) (DB::selectOne('
            SELECT COUNT(DISTINCT exam_users.user_id) AS c
            FROM exam_users
            LEFT JOIN exams ON exams.id = exam_users.exam_id
            WHERE exams.is_deleted = 0
        ')->c ?? 1);
        if ($cohort < 1) {
            $cohort = 1;
        }

        $assignedCount = (int) Exam::assignedTo($userId)->where('exams.is_deleted', 0)->count();
        $attemptCount = $list->count();
        if ($assignedCount < $attemptCount) {
            $assignedCount = $attemptCount;
        }

        $obtained = (float) ($youOverall->total_mark ?? 0);
        $maxMarks = (float) ($youOverall->totals_marks_for_exam ?? 0);
        $overallPct = $maxMarks > 0 ? round(($obtained / $maxMarks) * 100) : (int) round((float) $list->avg('percentage'));
        $grade = $this->letterGrade($overallPct);

        $right = (int) ($youOverall->right_answer ?? 0);
        $wrong = (int) ($youOverall->wrong_answer ?? 0);
        $skip = (int) ($youOverall->skip_answer ?? 0);
        $attemptedQ = $right + $wrong + $skip;
        $answeredQ = $right + $wrong;
        $accuracy = $answeredQ > 0 ? (int) round(($right / $answeredQ) * 100) : 0;

        $subjects = $this->subjectPerformance($userId);
        $topicRows = $this->topicPerformance($userId);
        $strongest = $subjects->sortByDesc('percentage')->first();
        $weakest = $subjects->sortBy('percentage')->first();

        $chrono = $list->sortBy(function ($row) {
            return $row->exam_date ?: $row->created_at;
        })->values();
        $trendLabels = [];
        $trendScores = [];
        foreach ($chrono as $row) {
            $trendLabels[] = $row->name ?: ('Test '.$row->id);
            $trendScores[] = (float) ($row->percentage ?? 0);
        }
        $improvement = 0;
        if (count($trendScores) >= 2) {
            $improvement = round(end($trendScores) - $trendScores[0], 1);
        }

        $tests = $this->paginateCollection(
            $chrono->reverse()->values()->map(function ($row) {
                $pct = (float) ($row->percentage ?? 0);
                $grade = $this->letterGrade($pct);
                return [
                    'id' => $row->id,
                    'user_id' => $row->user_id,
                    'name' => $row->name ?: 'Exam',
                    'date' => $row->exam_date ?: $row->created_at,
                    'marks' => (float) ($row->total_number ?? 0),
                    'max' => (float) ($row->total_mark ?? 0),
                    'percentage' => $pct,
                    'time' => $this->formatAttemptTime($row->total_time),
                    'result' => $grade['status'],
                    'result_class' => $grade['tone'],
                ];
            }),
            8,
            'tests_page',
            $request,
            'spr-tests'
        );

        $topics = $this->paginateCollection($topicRows, 10, 'topics_page', $request, 'spr-topics');

        $actions = $this->paginateCollection(
            $topicRows->sortBy('accuracy')->values()->map(function ($topic) {
                $target = min(95, max(70, (int) ceil($topic['accuracy'] / 5) * 5 + 15));
                if ($topic['accuracy'] >= 85) {
                    $action = 'Keep revising with mixed questions to hold this edge.';
                    $priority = 'Low';
                } elseif ($topic['accuracy'] >= 70) {
                    $action = 'Practise 15–20 mixed questions from this chapter this week.';
                    $priority = 'Medium';
                } else {
                    $action = 'Revise NCERT lines and typical traps, then re-attempt this chapter.';
                    $priority = 'High';
                }

                return [
                    'area' => trim($topic['subject'].' · '.$topic['topic']),
                    'current' => $topic['accuracy'],
                    'target' => $target,
                    'action' => $action,
                    'priority' => $priority,
                ];
            }),
            4,
            'actions_page',
            $request,
            'spr-actions'
        );

        $year = (int) date('Y');
        $month = (int) date('n');
        $academic = $month >= 4 ? $year.' – '.($year + 1) : ($year - 1).' – '.$year;

        return view('admin.student.exam_list', [
            'user' => $user,
            'list' => $list,
            'report' => [
                'academic_year' => $academic,
                'obtained' => $obtained,
                'max_marks' => $maxMarks,
                'overall_pct' => $overallPct,
                'grade' => $grade['letter'],
                'grade_status' => $grade['status'],
                'rank' => $rank,
                'cohort' => $cohort,
                'attempts' => $attemptCount,
                'assigned' => $assignedCount,
                'accuracy' => $accuracy,
                'right' => $right,
                'answered' => $answeredQ,
                'attempted_questions' => $attemptedQ,
                'improvement' => $improvement,
                'strongest' => $strongest,
                'weakest' => $weakest,
                'subjects' => $subjects,
                'topics' => $topics,
                'tests' => $tests,
                'trend_labels' => $trendLabels,
                'trend_scores' => $trendScores,
                'actions' => $actions,
                'avg_time' => $this->formatAttemptTime($list->avg('total_time')),
            ],
            'easy_toper_list' => $this->topperResultStats(1),
            'avg_easy_toper_list' => $this->averageResultStats(1),
            'my_easy_toper_list' => $this->studentResultStats($userId, 1),
            'medium_toper_list' => $this->topperResultStats(2),
            'avg_medium_toper_list' => $this->averageResultStats(2),
            'my_medium_toper_list' => $this->studentResultStats($userId, 2),
            'hard_toper_list' => $this->topperResultStats(3),
            'avg_hard_toper_list' => $this->averageResultStats(3),
            'my_hard_toper_list' => $this->studentResultStats($userId, 3),
            'topper_data' => $topperOverall,
            'average_data' => $averageOverall,
            'you_data' => $youOverall,
            'total_exam_attemped' => $attemptCount,
            'total_question_attemped' => Exam_result::where('user_id', $userId)->where('answer', '!=', '')->count(),
            'your_score' => $rank,
            'chart' => [
                'topper' => $this->scorePercent($topperOverall),
                'average' => $this->scorePercent($averageOverall),
                'you' => $this->scorePercent($youOverall),
                'your_mark' => $obtained,
                'total_mark' => $maxMarks,
            ],
        ]);
    }

    public function exam_details(Request $request)
    {
        $examUser = Exam_user::select([
                'exams.*',
                'exam_users.id as user_exam_id',
                'exam_users.user_id',
                'exam_users.exam_id as attempt_exam_id',
                'exam_users.total_answer',
                'exam_users.total_right_answer',
                'exam_users.total_number',
                'exam_users.percentage',
                'exam_users.rank',
            ])
            ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
            ->where('exam_users.id', $request->id)
            ->firstOrFail();

        $userId = (int) ($request->user_id ?: $examUser->user_id);
        if ((int) $examUser->user_id !== $userId) {
            abort(404);
        }

        $languageId = function_exists('getDefaultLanguage') ? (int) getDefaultLanguage() : 1;

        if ($examUser->question_paper_id) {
            $questions = Question_paper_question::select([
                    'question_paper_questions.id',
                    'question_paper_questions.question_id',
                    'question_paper_questions.question_number',
                    'questions.answer',
                    'questions.solution_video_link as video_link',
                ])
                ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
                ->where('question_paper_id', $examUser->question_paper_id)
                ->orderBy('question_paper_questions.question_number')
                ->orderBy('question_paper_questions.id')
                ->get();

            foreach ($questions as $row) {
                $detail = Question_detail::where('language_id', $languageId)
                    ->where('question_id', $row->question_id)
                    ->first();
                $row->question_text = $detail->question_text ?? '';
                $result = Exam_result::where('exam_user_id', $examUser->user_exam_id)
                    ->where('exam_question_id', $row->id)
                    ->first();
                $row->student_answer = $result->answer ?? '';
                $row->correct_answer = $row->answer;
            }
        } else {
            $questions = Offline_exam_question::where('exam_id', $examUser->id)
                ->orderBy('question_number')
                ->orderBy('id')
                ->get();

            foreach ($questions as $row) {
                $result = Exam_result::where('exam_user_id', $examUser->user_exam_id)
                    ->where('exam_question_id', $row->id)
                    ->first();
                $row->student_answer = $result->answer ?? '';
                $row->correct_answer = $row->answer;
                $row->video_link = $row->video_link ?? '';
            }
        }

        return view('admin.student.exam_details', [
            'user' => User::find($userId),
            'attempt' => $examUser,
            'list' => $questions,
        ]);
    }

    private function resultBaseQuery($difficulty = null)
    {
        return Exam_result::query()
            ->leftJoin('exam_users', 'exam_users.id', '=', 'exam_results.exam_user_id')
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('question_paper_questions', 'question_paper_questions.id', '=', 'exam_results.exam_question_id')
            ->leftJoin('question_papers', 'question_papers.id', '=', 'question_paper_questions.question_paper_id')
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('offline_exam_questions', 'offline_exam_questions.id', '=', 'exam_results.exam_question_id')
            ->where('exams.is_deleted', 0)
            ->when($difficulty !== null, function ($query) use ($difficulty) {
                $query->whereRaw('COALESCE(questions.difficulty_level, offline_exam_questions.difficulty_level) = ?', [$difficulty]);
            });
    }

    private function topperResultStats($difficulty = null)
    {
        $row = $this->resultBaseQuery($difficulty)
            ->select([
                DB::raw('count(*) as total_question'),
                DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                DB::raw('SUM(exam_results.result) as total_mark'),
                'exam_results.user_id',
            ])
            ->groupBy('exam_results.user_id')
            ->orderByDesc('total_mark')
            ->first();

        if ($row) {
            $row->totals_marks_for_exam = Exam_user::query()
                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                ->where('exam_users.user_id', $row->user_id)
                ->where('exams.is_deleted', 0)
                ->sum('exam_users.total_mark');
        }

        return $row;
    }

    private function averageResultStats($difficulty = null)
    {
        $sub = $this->resultBaseQuery($difficulty)
            ->select([
                DB::raw('count(*) as total_question'),
                DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                DB::raw('SUM(exam_results.result) as total_mark'),
                DB::raw('COALESCE(exams.totals_marks_for_exam, question_papers.totals_marks_for_exam) as totals_marks_for_exam'),
            ])
            ->groupBy('exam_results.user_id');

        return DB::query()
            ->fromSub($sub, 'user_stats')
            ->select([
                DB::raw('AVG(total_question) as total_question'),
                DB::raw('AVG(right_answer) as right_answer'),
                DB::raw('AVG(skip_answer) as skip_answer'),
                DB::raw('AVG(wrong_answer) as wrong_answer'),
                DB::raw('AVG(totals_marks_for_exam) as totals_marks_for_exam'),
                DB::raw('AVG(total_mark) as total_mark'),
            ])
            ->first();
    }

    private function studentResultStats($userId, $difficulty = null)
    {
        $row = $this->resultBaseQuery($difficulty)
            ->select([
                DB::raw('count(*) as total_question'),
                DB::raw('COUNT(CASE WHEN (exam_results.result != 0 AND exam_results.result != -1) THEN 1 END) as right_answer'),
                DB::raw('COUNT(CASE WHEN (exam_results.result = 0 AND exam_results.answer = 0) THEN 1 END) as skip_answer'),
                DB::raw('COUNT(CASE WHEN ((exam_results.result = 0 OR exam_results.result = -1) AND exam_results.answer != 0) THEN 1 END) as wrong_answer'),
                DB::raw('SUM(exam_results.result) as total_mark'),
            ])
            ->where('exam_results.user_id', $userId)
            ->groupBy('exam_results.user_id')
            ->first();

        if ($row) {
            $row->totals_marks_for_exam = Exam_user::query()
                ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                ->where('exam_users.user_id', $userId)
                ->where('exams.is_deleted', 0)
                ->sum('exam_users.total_mark');
        }

        return $row;
    }

    private function scorePercent($row): int
    {
        $mark = (float) ($row->total_mark ?? 0);
        $total = (float) ($row->totals_marks_for_exam ?? 0);
        if ($total <= 0) {
            return 0;
        }

        return (int) round(($mark / $total) * 100);
    }

    /**
     * @return array{letter:string,status:string,tone:string}
     */
    private function letterGrade(float $pct): array
    {
        if ($pct >= 90) {
            return ['letter' => 'A+', 'status' => 'Excellent', 'tone' => 'excellent'];
        }
        if ($pct >= 80) {
            return ['letter' => 'A', 'status' => 'Very Good', 'tone' => 'very-good'];
        }
        if ($pct >= 70) {
            return ['letter' => 'B', 'status' => 'Good', 'tone' => 'good'];
        }
        if ($pct >= 60) {
            return ['letter' => 'C', 'status' => 'Need Improvement', 'tone' => 'improve'];
        }

        return ['letter' => 'D', 'status' => 'Need Improvement', 'tone' => 'improve'];
    }

    private function formatAttemptTime($raw): string
    {
        $raw = (float) $raw;
        if ($raw <= 0) {
            return '—';
        }
        $minutes = $raw > 400 ? (int) round($raw / 60) : (int) round($raw);
        if ($minutes >= 60) {
            $hours = intdiv($minutes, 60);
            $mins = $minutes % 60;

            return $hours.'h '.($mins ? $mins.'m' : '');
        }

        return $minutes.' min';
    }

    private function subjectPerformance(int $userId)
    {
        return $this->resultBaseQuery(null)
            ->select([
                DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id) as subject_id'),
                DB::raw('MAX(subjects.name) as subject_name'),
                DB::raw('SUM(exam_results.result) as obtained'),
                DB::raw('COUNT(*) as attempted'),
                DB::raw('COUNT(CASE WHEN exam_results.result > 0 THEN 1 END) as correct'),
            ])
            ->leftJoin('subjects', function ($join) {
                $join->whereRaw('subjects.id = COALESCE(questions.subject_id, offline_exam_questions.subject_id)');
            })
            ->where('exam_results.user_id', $userId)
            ->groupBy(DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)'))
            ->get()
            ->filter(fn ($row) => $row->subject_name)
            ->map(function ($row) {
                $attempted = (int) $row->attempted;
                $obtained = (float) $row->obtained;
                $max = $attempted * 4;
                $pct = $max > 0 ? (int) round(($obtained / $max) * 100) : 0;
                $grade = $this->letterGrade($pct);

                return [
                    'name' => $row->subject_name,
                    'obtained' => (int) round($obtained),
                    'max' => $max,
                    'percentage' => $pct,
                    'grade' => $grade['letter'],
                    'status' => $grade['status'],
                    'tone' => $grade['tone'],
                ];
            })
            ->sortByDesc('percentage')
            ->values();
    }

    private function topicPerformance(int $userId)
    {
        return $this->resultBaseQuery(null)
            ->select([
                DB::raw('MAX(subjects.name) as subject_name'),
                DB::raw('MAX(chapters.name) as chapter_name'),
                DB::raw('MAX(topics.name) as topic_name'),
                DB::raw('COUNT(*) as attempted'),
                DB::raw('COUNT(CASE WHEN exam_results.result > 0 THEN 1 END) as correct'),
            ])
            ->leftJoin('subjects', function ($join) {
                $join->whereRaw('subjects.id = COALESCE(questions.subject_id, offline_exam_questions.subject_id)');
            })
            ->leftJoin('chapters', function ($join) {
                $join->whereRaw('chapters.id = COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)');
            })
            ->leftJoin('topics', function ($join) {
                $join->whereRaw('topics.id = COALESCE(questions.topic_id, offline_exam_questions.topic_id)');
            })
            ->where('exam_results.user_id', $userId)
            ->groupBy(DB::raw('COALESCE(questions.subject_id, offline_exam_questions.subject_id)'))
            ->groupBy(DB::raw('COALESCE(questions.chapter_id, offline_exam_questions.chapter_id)'))
            ->groupBy(DB::raw('COALESCE(questions.topic_id, offline_exam_questions.topic_id)'))
            ->get()
            ->map(function ($row) {
                $attempted = (int) $row->attempted;
                $correct = (int) $row->correct;
                $acc = $attempted > 0 ? (int) round(($correct / $attempted) * 100) : 0;
                if ($acc >= 85) {
                    $status = 'Strong';
                    $tone = 'strong';
                } elseif ($acc >= 70) {
                    $status = 'Good';
                    $tone = 'good';
                } elseif ($acc >= 55) {
                    $status = 'Needs Practice';
                    $tone = 'practice';
                } else {
                    $status = 'Weak';
                    $tone = 'weak';
                }
                $topic = trim((string) ($row->topic_name ?: $row->chapter_name ?: 'General'));

                return [
                    'subject' => $row->subject_name ?: 'Subject',
                    'topic' => $topic,
                    'accuracy' => $acc,
                    'attempted' => $attempted,
                    'correct' => $correct,
                    'status' => $status,
                    'tone' => $tone,
                ];
            })
            ->filter(fn ($row) => $row['attempted'] > 0)
            ->sortByDesc('attempted')
            ->values();
    }

    private function paginateCollection(Collection $items, int $perPage, string $pageName, Request $request, string $fragment = ''): LengthAwarePaginator
    {
        $total = $items->count();
        $lastPage = max(1, (int) ceil(($total ?: 1) / $perPage));
        $page = max(1, (int) $request->input($pageName, 1));
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $query = $request->query();
        unset($query[$pageName]);

        return new LengthAwarePaginator(
            $items->slice(($page - 1) * $perPage, $perPage)->values(),
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $query,
                'pageName' => $pageName,
                'fragment' => $fragment,
            ]
        );
    }

}
