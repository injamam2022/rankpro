<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('batches')) {
            Schema::create('batches', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('batch_user')) {
            Schema::create('batch_user', function (Blueprint $table) {
                $table->unsignedBigInteger('batch_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->unique(['batch_id', 'user_id']);
            });
        }

        if (!Schema::hasTable('batch_exam')) {
            Schema::create('batch_exam', function (Blueprint $table) {
                $table->unsignedBigInteger('batch_id')->index();
                $table->unsignedBigInteger('exam_id')->index();
                $table->unique(['batch_id', 'exam_id']);
            });
        }

        if (!Schema::hasTable('exam_assignments')) {
            Schema::create('exam_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('exam_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->unique(['exam_id', 'user_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('exam_assignments');
        Schema::dropIfExists('batch_exam');
        Schema::dropIfExists('batch_user');
        Schema::dropIfExists('batches');
    }
};
