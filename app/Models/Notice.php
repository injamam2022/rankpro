<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Notice extends Model
{
    protected $fillable = [
        'name','description','is_urgent','is_for_all',
        'status','created_at','updated_at'
    ];

    public function noticeUsers()
    {
        return $this->hasMany(Notice_user::class, 'notice_id');
    }

    public static function attachBroadcastForUser(int $userId): void
    {
        if ($userId < 1 || !Schema::hasTable('notices') || !Schema::hasColumn('notices', 'is_for_all')) {
            return;
        }

        $already = Notice_user::where('user_id', $userId)->pluck('notice_id');
        $broadcast = static::query()
            ->where('is_for_all', 1)
            ->where('status', 1)
            ->when($already->isNotEmpty(), function ($q) use ($already) {
                $q->whereNotIn('id', $already);
            })
            ->get(['id', 'created_at']);

        if ($broadcast->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];
        foreach ($broadcast as $notice) {
            $created = $notice->created_at ?: $now;
            $rows[] = [
                'notice_id' => $notice->id,
                'user_id' => $userId,
                'is_favorite' => 0,
                'is_archive' => 0,
                'is_urgent' => 0,
                'is_deleted' => 0,
                'status' => 1,
                'created_at' => $created,
                'updated_at' => $created,
            ];
        }

        Notice_user::insert($rows);
    }
}
