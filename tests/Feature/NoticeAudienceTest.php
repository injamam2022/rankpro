<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\NoticeController;
use App\Models\Notice;
use App\Models\Notice_user;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NoticeAudienceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('class_name')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('is_urgent')->default(0);
            $table->boolean('is_for_all')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
        Schema::create('notice_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notice_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('is_favorite')->default(0);
            $table->integer('is_archive')->default(0);
            $table->integer('is_urgent')->default(0);
            $table->integer('is_deleted')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
        Schema::create('exam_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        DB::table('users')->insert([
            ['id' => 10, 'first_name' => 'Ada', 'last_name' => 'One', 'class_name' => null, 'status' => 1],
            ['id' => 11, 'first_name' => 'Ben', 'last_name' => 'Two', 'class_name' => null, 'status' => 1],
            ['id' => 12, 'first_name' => 'Cara', 'last_name' => 'Three', 'class_name' => null, 'status' => 0],
        ]);
    }

    public function test_all_students_option_assigns_every_active_student(): void
    {
        $controller = app(NoticeController::class);
        $request = Request::create('/admin/notice/save', 'POST', [
            'name' => 'Batch announcement',
            'description' => 'Visible to everyone',
            'is_urgent' => 0,
            'status' => 1,
            'user_id' => 'all',
            'class_id' => 0,
        ]);

        $controller->save($request);

        $notice = Notice::where('name', 'Batch announcement')->first();
        $this->assertNotNull($notice);
        $this->assertSame(1, (int) $notice->is_for_all);
        $this->assertEqualsCanonicalizing([10, 11], Notice_user::where('notice_id', $notice->id)->pluck('user_id')->all());
    }

    public function test_specific_student_option_assigns_only_that_student(): void
    {
        $controller = app(NoticeController::class);
        $request = Request::create('/admin/notice/save', 'POST', [
            'name' => 'Personal note',
            'description' => 'Just Ada',
            'is_urgent' => 1,
            'status' => 1,
            'user_id' => 10,
        ]);

        $controller->save($request);

        $notice = Notice::where('name', 'Personal note')->first();
        $this->assertNotNull($notice);
        $this->assertSame(0, (int) $notice->is_for_all);
        $this->assertSame([10], Notice_user::where('notice_id', $notice->id)->pluck('user_id')->all());
    }

    public function test_new_student_receives_broadcast_notices(): void
    {
        $notice = Notice::create([
            'name' => 'Old circular',
            'description' => 'For everyone',
            'is_urgent' => 0,
            'is_for_all' => 1,
            'status' => 1,
        ]);

        $user = new User();
        $user->id = 11;
        Notice::attachBroadcastForUser((int) $user->id);

        $this->assertTrue(
            Notice_user::where('notice_id', $notice->id)->where('user_id', 11)->exists()
        );
    }
}
