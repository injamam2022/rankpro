<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam_result;
use App\Models\Subject;
use App\Support\AdminListQuery;
use Illuminate\Http\Request;

class ReportedQuestionController extends Controller
{
    public function list(Request $request)
    {
        $data = [];
        $data['q'] = AdminListQuery::q($request);
        $data['subject_id'] = AdminListQuery::id($request, 'subject_id');
        $data['subject_list'] = Subject::where('status', 1)->orderBy('name')->get();

        $languageId = function_exists('getDefaultLanguage') ? (int) getDefaultLanguage() : 1;

        $query = Exam_result::query()
            ->from('exam_results')
            ->select([
                'exam_results.id',
                'exam_results.exam_id',
                'exam_results.user_id',
                'exam_results.exam_user_id',
                'exam_results.question_id',
                'exam_results.exam_question_id',
                'exam_results.answer',
                'exam_results.result',
                'exam_results.reported',
                'exam_results.updated_at',
                'exam_results.created_at',
                'users.first_name',
                'users.last_name',
                'users.email_id',
                'exams.name as exam_name',
                'subjects.name as subject_name',
                'chapters.name as chapter_name',
                'question_details.question_text',
                'questions.answer as correct_answer',
            ])
            ->leftJoin('users', 'users.id', '=', 'exam_results.user_id')
            ->leftJoin('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->leftJoin('questions', 'questions.id', '=', 'exam_results.question_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'questions.subject_id')
            ->leftJoin('chapters', 'chapters.id', '=', 'questions.chapter_id')
            ->leftJoin('question_details', function ($join) use ($languageId) {
                $join->on('question_details.question_id', '=', 'exam_results.question_id')
                    ->where('question_details.language_id', '=', $languageId);
            })
            ->where('exam_results.reported', 1);

        if ($data['subject_id']) {
            $query->where('questions.subject_id', $data['subject_id']);
        }

        if ($data['q'] !== '') {
            $like = AdminListQuery::like($data['q']);
            $query->where(function ($w) use ($like) {
                $w->where('users.first_name', 'like', $like)
                    ->orWhere('users.last_name', 'like', $like)
                    ->orWhere('users.email_id', 'like', $like)
                    ->orWhere('exams.name', 'like', $like)
                    ->orWhere('subjects.name', 'like', $like)
                    ->orWhere('exam_results.question_id', 'like', $like)
                    ->orWhere('question_details.question_text', 'like', $like);
            });
        }

        $data['list'] = $query->orderByDesc('exam_results.updated_at')->get();

        return view('admin.reported_question.list', $data);
    }

    public function clear(Request $request)
    {
        $row = Exam_result::where('id', $request->id)->where('reported', 1)->first();
        if ($row) {
            $row->update(['reported' => 0]);
            toastr()->success('Report cleared successfully.');
        } else {
            toastr()->warning('Reported question not found.');
        }

        return redirect()->route('admin.reported_question');
    }
}
