<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\Exam_user;
use App\Services\StudentNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncExamNotifications extends Command
{
    protected $signature = 'notifications:sync-exams';

    protected $description = 'Create student notifications for upcoming and live assigned exams';

    public function handle(StudentNotificationService $notify): int
    {
        $now = now();
        $soon = $now->copy()->addDay();

        $exams = Exam::query()
            ->where('exams.is_deleted', 0)
            ->where('exams.status', 1)
            ->where(function ($q) {
                $q->whereNull('exams.exam_code')->orWhere('exams.exam_code', 'not like', 'CT-%');
            })
            ->listedForStudentPortal()
            ->get();

        foreach ($exams as $exam) {
            $start = $exam->scheduledStart();
            $end = $exam->scheduledEnd();
            $isLive = $start && $start->lte($now) && (! $end || $end->gte($now));
            $isUpcoming = $start && $start->gt($now) && $start->lte($soon);

            if (! $isLive && ! $isUpcoming) {
                continue;
            }

            $userIds = DB::table('exam_assignments')->where('exam_id', $exam->id)->pluck('user_id')
                ->merge(
                    DB::table('batch_exam')
                        ->join('batch_user', 'batch_user.batch_id', '=', 'batch_exam.batch_id')
                        ->where('batch_exam.exam_id', $exam->id)
                        ->pluck('batch_user.user_id')
                )
                ->unique()
                ->values();

            if ($userIds->isEmpty()) {
                $userIds = Exam_user::where('exam_id', $exam->id)->pluck('user_id');
            }

            $url = route('upcoming_exam');
            if ($isLive) {
                $notify->notify($userIds, StudentNotificationService::TYPE_EXAM_LIVE, $exam->name.' is live now', [
                    'body' => 'Open Give Exam to take this mock.',
                    'is_important' => true,
                    'notifiable_type' => Exam::class,
                    'notifiable_id' => $exam->id,
                    'action_url' => $url,
                ]);
            } elseif ($isUpcoming) {
                $when = $start ? $start->format('d M, h:i A') : '';
                $notify->notify($userIds, StudentNotificationService::TYPE_EXAM_UPCOMING, $exam->name.' starts soon', [
                    'body' => $when ? 'Starts '.$when : 'Scheduled within 24 hours.',
                    'is_important' => true,
                    'notifiable_type' => Exam::class,
                    'notifiable_id' => $exam->id,
                    'action_url' => $url,
                ]);
            }
        }

        $this->info('Exam notifications synced.');

        return self::SUCCESS;
    }
}
