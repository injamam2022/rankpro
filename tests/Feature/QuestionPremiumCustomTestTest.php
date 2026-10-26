<?php

namespace Tests\Feature;

use App\Models\Question;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class QuestionPremiumCustomTestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedTinyInteger('is_deleted')->default(0);
            $table->unsignedTinyInteger('is_premium')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('questions')->insert([
            ['id' => 1, 'subject_id' => 10, 'status' => 1, 'is_deleted' => 0, 'is_premium' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'subject_id' => 10, 'status' => 1, 'is_deleted' => 0, 'is_premium' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'subject_id' => 10, 'status' => 1, 'is_deleted' => 0, 'is_premium' => 0, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function test_non_premium_scope_excludes_premium_questions(): void
    {
        $ids = Question::query()->nonPremium()->orderBy('id')->pluck('id')->all();

        $this->assertSame([1, 3], $ids);
    }

    public function test_custom_test_questions_are_four_marks_each(): void
    {
        $this->assertSame(4, custom_test_marks_per_question());
    }
}
