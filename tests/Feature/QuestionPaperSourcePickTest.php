<?php

namespace Tests\Feature;

use App\Services\QuestionPaperMixService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class QuestionPaperSourcePickTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('chapter_id')->nullable();
            $table->unsignedInteger('topic_id')->nullable();
            $table->unsignedInteger('sub_topic_id')->nullable();
            $table->unsignedInteger('question_type_id')->nullable();
            $table->unsignedInteger('question_source_id')->nullable();
            $table->unsignedTinyInteger('difficulty_level')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedTinyInteger('is_deleted')->default(0);
            $table->timestamps();
        });
        Schema::create('question_question_source', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedInteger('question_source_id');
            $table->timestamps();
            $table->unique(['question_id', 'question_source_id']);
        });

        $now = now();
        foreach ([
            ['id' => 1, 'subject_id' => 10, 'question_source_id' => 7, 'status' => 1],
            ['id' => 2, 'subject_id' => 10, 'question_source_id' => 7, 'status' => 1],
            ['id' => 3, 'subject_id' => 11, 'question_source_id' => 7, 'status' => 1],
            ['id' => 4, 'subject_id' => 11, 'question_source_id' => 7, 'status' => 1],
            ['id' => 5, 'subject_id' => 10, 'question_source_id' => 8, 'status' => 1],
            ['id' => 6, 'subject_id' => 10, 'question_source_id' => 7, 'status' => 0],
        ] as $row) {
            DB::table('questions')->insert(array_merge([
                'chapter_id' => null,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_type_id' => 1,
                'difficulty_level' => 2,
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ], $row));
        }
    }

    public function test_source_pick_without_subjects_stays_inside_source(): void
    {
        $mix = new QuestionPaperMixService();
        $result = $mix->pickFromSource([], 7, 3);

        $this->assertSame(0, $result['shortfall']);
        $this->assertCount(3, $result['picked']);
        $ids = array_column($result['picked'], 'question_id');
        $this->assertEmpty(array_diff($ids, [1, 2, 3, 4]));
        $this->assertNotEmpty($result['auto_sections']);
    }

    public function test_source_pick_with_subject_rows_filters_source_and_subject(): void
    {
        $mix = new QuestionPaperMixService();
        $result = $mix->pickFromSource([
            0 => ['subject_id' => 10, 'total_no_of_question' => 2],
        ], 7, 2);

        $this->assertSame(0, $result['shortfall']);
        $this->assertCount(2, $result['picked']);
        $ids = array_column($result['picked'], 'question_id');
        sort($ids);
        $this->assertSame([1, 2], $ids);
    }

    public function test_source_pick_reports_shortfall_when_source_is_too_small(): void
    {
        $mix = new QuestionPaperMixService();
        $result = $mix->pickFromSource([], 8, 4);

        $this->assertSame(3, $result['shortfall']);
        $this->assertCount(1, $result['picked']);
        $this->assertSame(5, $result['picked'][0]['question_id']);
    }

    public function test_source_pick_includes_questions_linked_through_pivot(): void
    {
        DB::table('question_question_source')->insert([
            'question_id' => 1,
            'question_source_id' => 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mix = new QuestionPaperMixService();
        $result = $mix->pickFromSource([], 8, 2);

        $this->assertSame(0, $result['shortfall']);
        $ids = array_column($result['picked'], 'question_id');
        sort($ids);
        $this->assertSame([1, 5], $ids);
    }
}
