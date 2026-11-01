<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_package_exam_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_package_id');
            $table->unsignedBigInteger('course_schedule_id');
            $table->unsignedBigInteger('question_paper_id');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->unique(['course_package_id', 'course_schedule_id'], 'cpea_package_schedule_unique');
            $table->index('question_paper_id');
            $table->index('course_schedule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_package_exam_assignments');
    }
};
