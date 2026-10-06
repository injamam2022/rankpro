<?php

namespace App\View\Composers;

use App\Models\Batch;
use App\Models\Exam;
use App\Models\Exam_user;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentShellComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            return;
        }

        $data = $view->getData();
        $userId = Auth::id();

        if (!array_key_exists('neet_exam', $data) || empty($data['neet_exam'])) {
            $view->with('neet_exam', Setting::where('key_name', 'neet_exam')->first());
        }

        if (!array_key_exists('student_batches', $data) || $data['student_batches'] === null) {
            $view->with(
                'student_batches',
                Batch::query()
                    ->select('batches.id', 'batches.name')
                    ->join('batch_user', 'batch_user.batch_id', '=', 'batches.id')
                    ->where('batch_user.user_id', $userId)
                    ->where(function ($q) {
                        $q->where('batches.status', 1)->orWhereNull('batches.status');
                    })
                    ->orderBy('batches.name')
                    ->distinct()
                    ->get()
            );
        }

        if (!array_key_exists('mocks_given_count', $data)) {
            $view->with(
                'mocks_given_count',
                Exam_user::query()
                    ->leftJoin('exams', 'exams.id', '=', 'exam_users.exam_id')
                    ->where('exam_users.user_id', $userId)
                    ->where('exams.is_deleted', 0)
                    ->count()
            );
        }

        if (!array_key_exists('mocks_available_count', $data)) {
            $view->with(
                'mocks_available_count',
                Exam::assignedTo($userId)
                    ->where('exams.is_deleted', 0)
                    ->where('exams.status', 1)
                    ->where(function ($q) {
                        $q->whereNull('exams.exam_code')->orWhere('exams.exam_code', 'not like', 'CT-%');
                    })
                    ->listedForStudentPortal()
                    ->count()
            );
        }
    }
}
