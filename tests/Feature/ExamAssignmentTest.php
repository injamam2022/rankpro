<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\User;
use App\Http\Controllers\ExamsController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExamAssignmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
        });
        DB::table('users')->insert([['id' => 10], ['id' => 11]]);
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->integer('is_deleted')->default(0);
            $table->integer('type')->default(1);
        });
        (require database_path('migrations/2026_09_27_000001_create_exam_assignments.php'))->up();
        (require database_path('migrations/2026_09_28_000002_add_status_to_batches_table.php'))->up();
        DB::table('exams')->insert([['id' => 1], ['id' => 2], ['id' => 3]]);
    }

    public function test_only_direct_and_batch_assignments_grant_access()
    {
        $this->assertSame([], Exam::assignedTo(10)->pluck('id')->all());
        DB::table('exam_assignments')->insert(['exam_id' => 1, 'user_id' => 10]);
        DB::table('batches')->insert(['id' => 1, 'name' => 'Batch 1', 'status' => 1]);
        DB::table('batch_user')->insert(['batch_id' => 1, 'user_id' => 10]);
        DB::table('batch_exam')->insert([['batch_id' => 1, 'exam_id' => 1], ['batch_id' => 1, 'exam_id' => 2]]);
        $this->assertSame([1, 2], Exam::assignedTo(10)->orderBy('id')->pluck('id')->all());
        $this->assertSame([], Exam::assignedTo(11)->pluck('id')->all());
        DB::table('batch_user')->where('user_id', 10)->delete();
        $this->assertSame([1], Exam::assignedTo(10)->pluck('id')->all());
        DB::table('exam_assignments')->delete();
        $this->assertSame([], Exam::assignedTo(10)->pluck('id')->all());
    }

    public function test_unassigned_test_cannot_be_started_by_url()
    {
        $user = new User();
        $user->id = 10;
        $this->actingAs($user);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        app(ExamsController::class)->start_exam(Request::create('/start-exam', 'GET', ['id' => 1]));
    }

    public function test_admin_can_create_batch_and_replace_assignments()
    {
        $this->withSession(['adminAuth' => true])
            ->post(route('admin.batches.save'), ['name' => 'Morning batch', 'students' => [10], 'status' => 1])
            ->assertRedirect();
        $batchId = DB::table('batches')->value('id');
        $this->post(route('admin.exam_assignments.save'), ['exam_id' => 2, 'batches' => [$batchId], 'students' => [11]])
            ->assertRedirect();
        $this->assertTrue(Exam::assignedTo(10)->where('id', 2)->exists());
        $this->assertTrue(Exam::assignedTo(11)->where('id', 2)->exists());
        $this->post(route('admin.exam_assignments.save'), ['exam_id' => 2, 'batches' => ['']])->assertRedirect();
        $this->assertFalse(Exam::assignedTo(10)->where('id', 2)->exists());
        $this->assertFalse(Exam::assignedTo(11)->where('id', 2)->exists());

        $this->post(route('admin.batches.save'), ['name' => 'Delete me', 'students' => [10], 'status' => 1])->assertRedirect();
        $deleteId = DB::table('batches')->where('name', 'Delete me')->value('id');
        DB::table('batch_exam')->insert(['batch_id' => $deleteId, 'exam_id' => 3]);
        $this->post(route('admin.batches.delete'), ['batch_id' => $deleteId])->assertRedirect();
        $this->assertFalse(DB::table('batches')->where('id', $deleteId)->exists());
        $this->assertFalse(DB::table('batch_exam')->where('batch_id', $deleteId)->exists());
    }

    public function test_non_admin_cannot_assign_tests()
    {
        $this->post(route('admin.exam_assignments.save'), ['exam_id' => 1, 'students' => [10]])
            ->assertRedirect('/webadmin');
        $this->assertSame(0, DB::table('exam_assignments')->count());
    }
}
