<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notices') && !Schema::hasColumn('notices', 'is_for_all')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->boolean('is_for_all')->default(0)->after('is_urgent');
            });
        }

        if (!Schema::hasTable('notices') || !Schema::hasTable('notice_users') || !Schema::hasTable('users')) {
            return;
        }

        $studentIds = DB::table('users')->where('status', 1)->pluck('id');
        if ($studentIds->isEmpty()) {
            return;
        }

        $now = now();
        $activeNotices = DB::table('notices')->where('status', 1)->get(['id', 'created_at']);
        foreach ($activeNotices as $notice) {
            DB::table('notices')->where('id', $notice->id)->update(['is_for_all' => 1]);
            $existing = DB::table('notice_users')->where('notice_id', $notice->id)->pluck('user_id');
            $missing = $studentIds->diff($existing);
            $rows = [];
            foreach ($missing as $userId) {
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
            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table('notice_users')->insert($chunk);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('notices') && Schema::hasColumn('notices', 'is_for_all')) {
            Schema::table('notices', function (Blueprint $table) {
                $table->dropColumn('is_for_all');
            });
        }
    }
};
