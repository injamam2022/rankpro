<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('custom_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('subject_id')->index();
            $table->string('name');
            $table->unsignedInteger('question_count')->default(0);
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->decimal('marks_per_question', 8, 2)->default(4);
            $table->boolean('negative_marking')->default(false);
            $table->decimal('negative_marks', 8, 2)->default(1);
            $table->unsignedTinyInteger('difficulty')->nullable(); // 1 easy 2 medium 3 hard, null = mix
            $table->unsignedBigInteger('question_paper_id')->nullable()->index();
            $table->unsignedBigInteger('exam_id')->nullable()->index();
            $table->unsignedBigInteger('exam_user_id')->nullable()->index();
            $table->string('status', 20)->default('draft'); // draft, ready, attempted
            $table->json('selection')->nullable(); // [{chapter_id, topic_ids:[]}]
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_tests');
    }
};
