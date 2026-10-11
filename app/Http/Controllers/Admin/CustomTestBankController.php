<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomTest;
use App\Models\Question_detail;
use App\Models\Question_paper;
use App\Models\Question_paper_question;
use App\Services\QuestionPaperPdfService;
use App\Support\AdminListQuery;
use Illuminate\Http\Request;

class CustomTestBankController extends Controller
{
    public function list(Request $request)
    {
        $q = AdminListQuery::q($request);
        $perPage = AdminListQuery::perPage($request);

        $query = CustomTest::query()
            ->with(['user', 'subject'])
            ->whereNotNull('question_paper_id');

        if ($q !== '') {
            $like = AdminListQuery::like($q);
            $query->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('status', 'like', $like)
                    ->orWhereHas('user', function ($u) use ($like) {
                        $u->where('first_name', 'like', $like)
                            ->orWhere('last_name', 'like', $like)
                            ->orWhere('rankpro_id', 'like', $like);
                    });
            });
        }

        $list = $query->orderByDesc('id')->paginate($perPage)->withQueryString();

        return view('admin.custom_test_bank.list', [
            'list' => $list,
            'q' => $q,
            'per_page' => $perPage,
        ]);
    }

    /**
     * View paper questions with the student's submitted answers.
     */
    public function answers(Request $request)
    {
        $custom = CustomTest::with(['user', 'examUser', 'subject'])
            ->where('id', (int) $request->id)
            ->whereNotNull('question_paper_id')
            ->first();

        if (!$custom) {
            toastr()->error('Custom test not found.');
            return redirect()->route('admin.custom_test_bank');
        }

        $languageId = function_exists('getDefaultLanguage') ? (int) getDefaultLanguage() : 1;
        $examUserId = (int) ($custom->exam_user_id ?? 0);

        $slots = Question_paper_question::query()
            ->select([
                'question_paper_questions.id as paper_question_id',
                'question_paper_questions.question_number',
                'question_paper_questions.question_id',
                'questions.answer as correct_answer',
                'questions.solution_video_link',
                'exam_results.answer as student_answer',
                'exam_results.result as result_marks',
            ])
            ->leftJoin('questions', 'questions.id', '=', 'question_paper_questions.question_id')
            ->leftJoin('exam_results', function ($join) use ($examUserId) {
                $join->on('exam_results.exam_question_id', '=', 'question_paper_questions.id');
                if ($examUserId > 0) {
                    $join->where('exam_results.exam_user_id', '=', $examUserId);
                } else {
                    $join->whereRaw('1 = 0');
                }
            })
            ->where('question_paper_questions.question_paper_id', $custom->question_paper_id)
            ->orderBy('question_paper_questions.question_number')
            ->orderBy('question_paper_questions.id')
            ->get();

        $questionIds = $slots->pluck('question_id')->filter()->unique()->values()->all();
        $details = Question_detail::whereIn('question_id', $questionIds ?: [0])
            ->where('language_id', $languageId)
            ->get()
            ->keyBy('question_id');

        $list = $slots->map(function ($slot) use ($details) {
            $detail = $details->get($slot->question_id);
            $slot->question_text = $detail->question_text ?? '';
            $slot->question_image = $detail->question_image ?? null;
            $slot->option1 = $detail->option1 ?? '';
            $slot->option2 = $detail->option2 ?? '';
            $slot->option3 = $detail->option3 ?? '';
            $slot->option4 = $detail->option4 ?? '';
            $slot->is_option1_image = (int) ($detail->is_option1_image ?? 0);
            $slot->is_option2_image = (int) ($detail->is_option2_image ?? 0);
            $slot->is_option3_image = (int) ($detail->is_option3_image ?? 0);
            $slot->is_option4_image = (int) ($detail->is_option4_image ?? 0);

            $student = $slot->student_answer;
            $correct = $slot->correct_answer;
            if ($student === null || $student === '') {
                $slot->result_label = 'Unattempted';
                $slot->result_class = 'secondary';
            } elseif ((string) $student === (string) $correct) {
                $slot->result_label = 'Correct';
                $slot->result_class = 'success';
            } else {
                $slot->result_label = 'Incorrect';
                $slot->result_class = 'danger';
            }

            return $slot;
        });

        $student = $custom->user;
        $studentName = $student
            ? trim(($student->first_name ?? '').' '.($student->last_name ?? ''))
            : '';

        return view('admin.custom_test_bank.answers', [
            'custom' => $custom,
            'list' => $list,
            'studentName' => $studentName !== '' ? $studentName : '—',
            'studentId' => $student->rankpro_id ?? '—',
            'hasAttempt' => $examUserId > 0,
        ]);
    }

    public function download_question(Request $request, QuestionPaperPdfService $pdfService)
    {
        return $this->downloadPaperPdf($request, 'question', $pdfService);
    }

    public function download_answer(Request $request, QuestionPaperPdfService $pdfService)
    {
        return $this->downloadPaperPdf($request, 'answer', $pdfService);
    }

    protected function downloadPaperPdf(Request $request, string $kind, QuestionPaperPdfService $pdfService)
    {
        $custom = CustomTest::where('id', (int) $request->id)
            ->whereNotNull('question_paper_id')
            ->first();

        if (!$custom || !(int) $custom->question_paper_id) {
            toastr()->error('Custom test paper not found.');
            return redirect()->route('admin.custom_test_bank');
        }

        $paper = Question_paper::where('id', (int) $custom->question_paper_id)
            ->where('is_deleted', 0)
            ->first();

        if (!$paper) {
            toastr()->error('Question paper not found for this custom test.');
            return redirect()->route('admin.custom_test_bank.answers', ['id' => $custom->id]);
        }

        try {
            return $pdfService->download($paper, $kind);
        } catch (\Throwable $e) {
            report($e);
            toastr()->error('Could not generate the PDF. Please try again.');
            return redirect()->route('admin.custom_test_bank.answers', ['id' => $custom->id]);
        }
    }
}
