<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // Reuse empty placeholder parent rows (ids 10, 11) if present; otherwise insert.
        $this->upsertParent(10, 'Course Packages', 'course-package', 10, $now);
        $this->upsertParent(11, 'Batch Master', 'batch-master', 11, $now);

        $coursePackageId = (int) DB::table('admin_user_accesses')->where('type', 'course-package')->value('id');
        $batchMasterId = (int) DB::table('admin_user_accesses')->where('type', 'batch-master')->value('id');

        $testAssignmentId = (int) DB::table('admin_user_accesses')->where('type', 'test-assignment')->value('id');
        if (!$testAssignmentId) {
            $testAssignmentId = (int) DB::table('admin_user_accesses')->insertGetId([
                'name' => 'Test Assignment',
                'type' => 'test-assignment',
                'parent_id' => 0,
                'is_list' => 0,
                'is_add' => 0,
                'is_edit' => 0,
                'is_delete' => 0,
                'is_export' => 0,
                'order' => 12,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            DB::table('admin_user_accesses')->where('id', $testAssignmentId)->update([
                'name' => 'Test Assignment',
                'parent_id' => 0,
                'status' => 1,
                'order' => 12,
                'updated_at' => $now,
            ]);
        }

        $children = [
            [
                'name' => 'Packages',
                'type' => 'course-package-list',
                'parent_id' => $coursePackageId,
                'order' => 1,
            ],
            [
                'name' => 'Package Orders',
                'type' => 'course-package-orders-list',
                'parent_id' => $coursePackageId,
                'order' => 2,
            ],
            [
                'name' => 'Batch Master',
                'type' => 'batch-master-list',
                'parent_id' => $batchMasterId,
                'order' => 1,
            ],
            [
                'name' => 'Test Assignment',
                'type' => 'test-assignment-list',
                'parent_id' => $testAssignmentId,
                'order' => 1,
            ],
        ];

        foreach ($children as $child) {
            $existing = DB::table('admin_user_accesses')->where('type', $child['type'])->first();
            $payload = [
                'name' => $child['name'],
                'type' => $child['type'],
                'parent_id' => $child['parent_id'],
                'is_list' => 1,
                'is_add' => 1,
                'is_edit' => 1,
                'is_delete' => 1,
                'is_export' => 0,
                'order' => $child['order'],
                'status' => 1,
                'updated_at' => $now,
            ];
            if ($existing) {
                DB::table('admin_user_accesses')->where('id', $existing->id)->update($payload);
            } else {
                $payload['created_at'] = $now;
                DB::table('admin_user_accesses')->insert($payload);
            }
        }

        // Roles that already had every original parent menu get the new modules too.
        $originalParentIds = DB::table('admin_user_accesses')
            ->where('parent_id', 0)
            ->where('status', 1)
            ->whereIn('type', [
                'location', 'ranker', 'student', 'payment', 'question',
                'offline-exam', 'cms', 'online-exam', 'coupon',
            ])
            ->pluck('id');

        $newAccesses = DB::table('admin_user_accesses')
            ->where('status', 1)
            ->whereIn('type', [
                'course-package', 'course-package-list', 'course-package-orders-list',
                'batch-master', 'batch-master-list',
                'test-assignment', 'test-assignment-list',
            ])
            ->get();

        foreach (DB::table('admin_user_roles')->where('status', 1)->get() as $role) {
            $haveCount = DB::table('admin_user_role_accesses')
                ->where('admin_user_role_id', $role->id)
                ->whereIn('admin_user_access_id', $originalParentIds)
                ->count();
            if ($haveCount < $originalParentIds->count()) {
                continue;
            }
            foreach ($newAccesses as $access) {
                $exists = DB::table('admin_user_role_accesses')
                    ->where('admin_user_role_id', $role->id)
                    ->where('admin_user_access_id', $access->id)
                    ->exists();
                if ($exists) {
                    continue;
                }
                $row = [
                    'admin_user_role_id' => $role->id,
                    'admin_user_access_id' => $access->id,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                if ((int) $access->parent_id !== 0) {
                    $row['parent_id'] = $access->parent_id;
                    $row['is_list'] = 1;
                    $row['is_add'] = 1;
                    $row['is_edit'] = 1;
                    $row['is_delete'] = 1;
                    $row['is_export'] = 0;
                }
                DB::table('admin_user_role_accesses')->insert($row);
            }
        }
    }

    public function down(): void
    {
        $types = [
            'course-package-list',
            'course-package-orders-list',
            'batch-master-list',
            'test-assignment-list',
            'course-package',
            'batch-master',
            'test-assignment',
        ];

        $ids = DB::table('admin_user_accesses')->whereIn('type', $types)->pluck('id');
        if ($ids->isNotEmpty()) {
            DB::table('admin_user_role_accesses')->whereIn('admin_user_access_id', $ids)->delete();
            DB::table('admin_user_accesses')->whereIn('id', $ids)->delete();
        }
    }

    private function upsertParent(int $preferredId, string $name, string $type, int $order, $now): void
    {
        $byType = DB::table('admin_user_accesses')->where('type', $type)->first();
        if ($byType) {
            DB::table('admin_user_accesses')->where('id', $byType->id)->update([
                'name' => $name,
                'parent_id' => 0,
                'is_list' => 0,
                'is_add' => 0,
                'is_edit' => 0,
                'is_delete' => 0,
                'order' => $order,
                'status' => 1,
                'updated_at' => $now,
            ]);
            return;
        }

        $placeholder = DB::table('admin_user_accesses')->where('id', $preferredId)->first();
        if ($placeholder && (empty($placeholder->type) || empty($placeholder->name))) {
            DB::table('admin_user_accesses')->where('id', $preferredId)->update([
                'name' => $name,
                'type' => $type,
                'parent_id' => 0,
                'is_list' => 0,
                'is_add' => 0,
                'is_edit' => 0,
                'is_delete' => 0,
                'is_export' => 0,
                'order' => $order,
                'status' => 1,
                'updated_at' => $now,
            ]);
            return;
        }

        DB::table('admin_user_accesses')->insert([
            'name' => $name,
            'type' => $type,
            'parent_id' => 0,
            'is_list' => 0,
            'is_add' => 0,
            'is_edit' => 0,
            'is_delete' => 0,
            'is_export' => 0,
            'order' => $order,
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
};
