<?php

namespace App\Services;

use App\Models\StudentNotification;
use Illuminate\Support\Collection;

class StudentNotificationService
{
    public const TYPE_ADMIN_NOTICE = 'admin_notice';
    public const TYPE_EXAM_ASSIGNED = 'exam_assigned';
    public const TYPE_EXAM_UPCOMING = 'exam_upcoming';
    public const TYPE_EXAM_LIVE = 'exam_live';
    public const TYPE_RESULT_READY = 'result_ready';

    /**
     * @param  array<int,int>|Collection  $userIds
     */
    public function notify($userIds, string $type, string $title, array $extra = []): void
    {
        $ids = collect($userIds)->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];
        foreach ($ids as $userId) {
            $exists = StudentNotification::query()
                ->where('user_id', $userId)
                ->where('type', $type)
                ->when(! empty($extra['notifiable_id']), function ($q) use ($extra) {
                    $q->where('notifiable_id', $extra['notifiable_id'])
                        ->where('notifiable_type', $extra['notifiable_type'] ?? null);
                })
                ->exists();
            if ($exists) {
                continue;
            }
            $rows[] = [
                'user_id' => (int) $userId,
                'type' => $type,
                'title' => $title,
                'body' => $extra['body'] ?? null,
                'is_important' => ! empty($extra['is_important']) ? 1 : 0,
                'notifiable_type' => $extra['notifiable_type'] ?? null,
                'notifiable_id' => $extra['notifiable_id'] ?? null,
                'action_url' => $extra['action_url'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (count($rows) >= 500) {
                StudentNotification::insert($rows);
                $rows = [];
            }
        }
        if ($rows) {
            StudentNotification::insert($rows);
        }
    }

    public function unreadCount(int $userId): int
    {
        return StudentNotification::where('user_id', $userId)->unread()->count();
    }

    public function latest(int $userId, ?string $tab = null, int $limit = 15)
    {
        $q = StudentNotification::where('user_id', $userId)->orderByDesc('id');
        if ($tab === 'upcoming') {
            $q->whereIn('type', [self::TYPE_EXAM_ASSIGNED, self::TYPE_EXAM_UPCOMING, self::TYPE_EXAM_LIVE]);
        } elseif ($tab === 'important') {
            $q->where('is_important', 1);
        }

        return $q->limit($limit)->get();
    }

    public function markRead(int $userId, ?int $id = null): void
    {
        $q = StudentNotification::where('user_id', $userId)->unread();
        if ($id) {
            $q->where('id', $id);
        }
        $q->update(['read_at' => now()]);
    }
}
