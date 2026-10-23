<?php

namespace Tests\Feature;

use App\Models\Exam;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OfflineExamFormTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('exam_code')->nullable();
            $table->integer('type')->default(2);
            $table->integer('status')->default(1);
            $table->integer('is_deleted')->default(0);
            $table->integer('is_ended')->default(0);
            $table->date('exam_date')->nullable();
            $table->time('exam_time')->nullable();
            $table->date('exam_end_date')->nullable();
            $table->time('exam_end_time')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->integer('question_type')->nullable();
            $table->unsignedBigInteger('question_paper_id')->nullable();
            $table->integer('no_of_question')->nullable();
            $table->string('price')->nullable();
            $table->string('dis_price')->nullable();
            $table->string('tax')->nullable();
            $table->text('exam_instructions')->nullable();
            $table->text('description')->nullable();
            $table->text('result_title')->nullable();
            $table->text('result_description')->nullable();
            $table->text('result_declaration')->nullable();
            $table->string('exam_logo')->nullable();
            $table->string('landing_icon')->nullable();
            $table->integer('is_in_footer')->default(0);
            $table->integer('is_trending')->default(0);
            $table->timestamps();
        });
        Schema::create('exam_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('language_id')->nullable();
            $table->timestamps();
        });
        Schema::create('exam_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->timestamps();
        });
        Schema::create('exam_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->string('date_name')->nullable();
            $table->timestamps();
        });
    }

    private function examPayload(array $overrides = []): array
    {
        return array_merge([
            'exam_code' => 201,
            'name' => 'Offline Mock',
            'exam_date' => now()->addDay()->toDateString(),
            'exam_time' => '10:00',
            'exam_end_date' => now()->addDay()->toDateString(),
            'exam_end_time' => '13:00',
            'price' => '500',
            'question_type' => 1,
            'question_paper_id' => 5,
            'status' => 1,
            'is_trending' => 0,
        ], $overrides);
    }

    public function test_admin_can_save_offline_exam_without_omr_and_with_end_schedule()
    {
        $this->withSession(['adminAuth' => true])
            ->post(route('admin.offline_exam.save'), $this->examPayload())
            ->assertRedirect(route('admin.offline_exam'));

        $exam = Exam::where('exam_code', 201)->first();
        $this->assertNotNull($exam);
        $this->assertSame(2, (int) $exam->type);
        $this->assertNull($exam->exam_logo);
        $this->assertNull($exam->landing_icon);
        $this->assertNull($exam->result_title);
        $this->assertSame(now()->addDay()->toDateString(), $exam->exam_end_date);
        $this->assertSame('13:00:00', $exam->exam_end_time);
    }

    public function test_save_rejects_end_before_start()
    {
        $this->withSession(['adminAuth' => true])
            ->from(route('admin.offline_exam.add'))
            ->post(route('admin.offline_exam.save'), $this->examPayload([
                'exam_end_date' => now()->toDateString(),
                'exam_end_time' => '08:00',
            ]))
            ->assertRedirect(route('admin.offline_exam.add'));

        $this->assertSame(0, Exam::count());
    }

    public function test_update_saves_end_schedule_without_omr()
    {
        $this->withSession(['adminAuth' => true])
            ->post(route('admin.offline_exam.save'), $this->examPayload())
            ->assertRedirect(route('admin.offline_exam'));

        $exam = Exam::where('exam_code', 201)->first();

        $this->post(route('admin.offline_exam.update'), $this->examPayload([
            'id' => $exam->id,
            'name' => 'Offline Mock Updated',
            'exam_end_time' => '14:30',
            'result_title' => '',
            'result_description' => '',
            'result_declaration' => '',
        ]))->assertRedirect(route('admin.offline_exam'));

        $exam->refresh();
        $this->assertSame('Offline Mock Updated', $exam->name);
        $this->assertSame('14:30:00', $exam->exam_end_time);
        $this->assertSame('', (string) $exam->result_title);
    }
}
