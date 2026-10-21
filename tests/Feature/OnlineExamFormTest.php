<?php

namespace Tests\Feature;

use App\Models\Exam;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OnlineExamFormTest extends TestCase
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
            $table->string('name')->nullable();
            $table->integer('exam_code')->nullable();
            $table->integer('type')->default(1);
            $table->integer('status')->default(1);
            $table->integer('is_deleted')->default(0);
            $table->integer('is_ended')->default(0);
            $table->date('exam_date')->nullable();
            $table->time('exam_time')->nullable();
            $table->date('exam_end_date')->nullable();
            $table->time('exam_end_time')->nullable();
            $table->string('price')->nullable();
            $table->string('dis_price')->nullable();
            $table->string('tax')->nullable();
            $table->unsignedBigInteger('question_paper_id')->nullable();
            $table->text('exam_instructions')->nullable();
            $table->text('description')->nullable();
            $table->text('result_title')->nullable();
            $table->text('result_description')->nullable();
            $table->text('result_declaration')->nullable();
            $table->string('exam_logo')->nullable();
            $table->string('landing_icon')->nullable();
            $table->integer('is_in_footer')->default(0);
            $table->integer('is_trending')->default(0);
            $table->integer('is_proctored')->default(0);
            $table->integer('proctoring_max_violations')->default(5);
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_27_000001_create_exam_assignments.php'))->up();
        (require database_path('migrations/2026_09_28_000002_add_status_to_batches_table.php'))->up();
        DB::table('batches')->insert(['id' => 1, 'name' => 'Morning', 'status' => 1]);
        DB::table('batch_user')->insert(['batch_id' => 1, 'user_id' => 10]);
    }

    private function examPayload(array $overrides = []): array
    {
        return array_merge([
            'exam_code' => 101,
            'name' => 'NEET Mock',
            'exam_date' => now()->addDay()->toDateString(),
            'exam_time' => '10:00',
            'exam_end_date' => now()->addDay()->toDateString(),
            'exam_end_time' => '13:00',
            'price' => '1000',
            'batch_id' => 1,
            'question_paper_id' => 5,
            'status' => 1,
            'is_trending' => 0,
            'is_proctored' => 0,
        ], $overrides);
    }

    public function test_admin_can_save_online_exam_without_icons_or_omr()
    {
        $this->withSession(['adminAuth' => true])
            ->post(route('admin.online_exam.save'), $this->examPayload())
            ->assertRedirect(route('admin.online_exam'));

        $exam = Exam::where('exam_code', 101)->first();
        $this->assertNotNull($exam);
        $this->assertNull($exam->exam_logo);
        $this->assertNull($exam->result_title);
        $this->assertSame(now()->addDay()->toDateString(), $exam->exam_end_date);
        $this->assertSame('13:00:00', $exam->exam_end_time);
        $this->assertTrue(DB::table('batch_exam')->where('exam_id', $exam->id)->where('batch_id', 1)->exists());
        $this->assertTrue(Exam::assignedTo(10)->where('id', $exam->id)->exists());
        $this->assertFalse(Exam::assignedTo(11)->where('id', $exam->id)->exists());
    }

    public function test_save_requires_batch_and_rejects_end_before_start()
    {
        $this->withSession(['adminAuth' => true])
            ->from(route('admin.online_exam.add'))
            ->post(route('admin.online_exam.save'), $this->examPayload(['batch_id' => '']))
            ->assertRedirect(route('admin.online_exam.add'));
        $this->assertSame(0, Exam::count());

        $this->withSession(['adminAuth' => true])
            ->from(route('admin.online_exam.add'))
            ->post(route('admin.online_exam.save'), $this->examPayload([
                'exam_end_date' => now()->toDateString(),
                'exam_end_time' => '08:00',
            ]))
            ->assertRedirect(route('admin.online_exam.add'));
        $this->assertSame(0, Exam::count());
    }

    public function test_update_keeps_schedule_batch_and_direct_student_assignments()
    {
        $this->withSession(['adminAuth' => true])
            ->post(route('admin.online_exam.save'), $this->examPayload())
            ->assertRedirect(route('admin.online_exam'));

        $exam = Exam::where('exam_code', 101)->first();
        DB::table('exam_assignments')->insert(['exam_id' => $exam->id, 'user_id' => 11]);

        $this->post(route('admin.online_exam.update'), $this->examPayload([
            'id' => $exam->id,
            'name' => 'NEET Mock Updated',
            'exam_end_time' => '14:30',
            'result_title' => '',
        ]))->assertRedirect(route('admin.online_exam'));

        $exam->refresh();
        $this->assertSame('NEET Mock Updated', $exam->name);
        $this->assertSame('14:30:00', $exam->exam_end_time);
        $this->assertTrue(DB::table('batch_exam')->where('exam_id', $exam->id)->where('batch_id', 1)->exists());
        $this->assertTrue(Exam::assignedTo(10)->where('id', $exam->id)->exists());
        $this->assertTrue(Exam::assignedTo(11)->where('id', $exam->id)->exists());
        $this->assertTrue(Exam::listedForStudentPortal()->where('id', $exam->id)->exists());
    }
}
