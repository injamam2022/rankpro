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
            $table->date('exam_date')->nullable();
            $table->time('exam_time')->nullable();
            $table->date('exam_end_date')->nullable();
            $table->time('exam_end_time')->nullable();
            $table->integer('is_ended')->default(0);
            $table->timestamps();
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
        $this->post(route('admin.exam_assignments.save'), [
            'exam_id' => 2,
            'batches' => [$batchId],
            'students' => [11],
            'exam_date' => now()->addDay()->toDateString(),
            'exam_time' => '10:30',
            'exam_end_date' => now()->addDay()->toDateString(),
            'exam_end_time' => '12:30',
        ])->assertRedirect();
        $this->assertSame(now()->addDay()->toDateString(), DB::table('exams')->where('id', 2)->value('exam_date'));
        $this->assertSame('12:30:00', DB::table('exams')->where('id', 2)->value('exam_end_time'));
        $this->assertTrue(Exam::assignedTo(10)->where('id', 2)->exists());
        $this->assertTrue(Exam::assignedTo(11)->where('id', 2)->exists());
        $this->post(route('admin.exam_assignments.save'), [
            'exam_id' => 2,
            'batches' => [''],
            'exam_date' => now()->addDay()->toDateString(),
            'exam_time' => '10:30',
            'exam_end_date' => now()->addDay()->toDateString(),
            'exam_end_time' => '12:30',
        ])->assertRedirect();
        $this->assertFalse(Exam::assignedTo(10)->where('id', 2)->exists());
        $this->assertFalse(Exam::assignedTo(11)->where('id', 2)->exists());

        $this->post(route('admin.batches.save'), ['name' => 'Delete me', 'students' => [10], 'status' => 1])->assertRedirect();
        $deleteId = DB::table('batches')->where('name', 'Delete me')->value('id');
        DB::table('batch_exam')->insert(['batch_id' => $deleteId, 'exam_id' => 3]);
        $this->post(route('admin.batches.delete'), ['batch_id' => $deleteId])->assertRedirect();
        $this->assertFalse(DB::table('batches')->where('id', $deleteId)->exists());
        $this->assertFalse(DB::table('batch_exam')->where('batch_id', $deleteId)->exists());
    }

    public function test_portal_keeps_recent_assigned_tests_and_hides_old_ones()
    {
        DB::table('exams')->where('id', 1)->update([
            'exam_date' => now()->subDay()->toDateString(),
            'exam_time' => '09:00:00',
            'is_ended' => 0,
        ]);
        DB::table('exams')->where('id', 2)->update([
            'exam_date' => now()->subDays(30)->toDateString(),
            'exam_time' => '09:00:00',
            'exam_end_date' => now()->addDay()->toDateString(),
            'exam_end_time' => '18:00:00',
            'is_ended' => 0,
        ]);
        DB::table('exams')->where('id', 3)->update([
            'exam_date' => now()->addDay()->toDateString(),
            'exam_time' => '18:00:00',
            'is_ended' => 0,
        ]);
        DB::table('exam_assignments')->insert([
            ['exam_id' => 1, 'user_id' => 10],
            ['exam_id' => 2, 'user_id' => 10],
            ['exam_id' => 3, 'user_id' => 10],
        ]);

        $this->assertSame([1, 2, 3], Exam::assignedTo(10)->listedForStudentPortal()->orderBy('id')->pluck('id')->all());
        DB::table('exams')->where('id', 2)->update([
            'exam_end_date' => now()->subDay()->toDateString(),
            'exam_end_time' => '08:00:00',
        ]);
        $this->assertSame([1, 3], Exam::assignedTo(10)->listedForStudentPortal()->orderBy('id')->pluck('id')->all());

        $future = new Exam();
        $future->exam_date = now()->addDay()->toDateString();
        $future->exam_time = '18:00:00';
        $this->assertFalse($future->canBeStarted());

        $open = new Exam();
        $open->exam_date = now()->subDay()->toDateString();
        $open->exam_time = '09:00:00';
        $open->exam_end_date = now()->addHour()->toDateString();
        $open->exam_end_time = now()->addHour()->format('H:i:s');
        $this->assertTrue($open->canBeStarted());

        $closed = new Exam();
        $closed->exam_date = now()->subDays(2)->toDateString();
        $closed->exam_time = '09:00:00';
        $closed->exam_end_date = now()->subDay()->toDateString();
        $closed->exam_end_time = '10:00:00';
        $this->assertFalse($closed->canBeStarted());
    }

    public function test_non_admin_cannot_assign_tests()
    {
        $this->post(route('admin.exam_assignments.save'), ['exam_id' => 1, 'students' => [10]])
            ->assertRedirect('/webadmin');
        $this->assertSame(0, DB::table('exam_assignments')->count());
    }
}
