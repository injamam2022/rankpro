<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('exams') && !Schema::hasColumn('exams', 'is_proctored')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->tinyInteger('is_proctored')->default(0);
                $table->unsignedInteger('proctoring_max_violations')->default(5);
            });
        }

        if (Schema::hasTable('exam_users') && !Schema::hasColumn('exam_users', 'tab_switch_count')) {
            Schema::table('exam_users', function (Blueprint $table) {
                $table->unsignedInteger('tab_switch_count')->default(0);
                $table->string('proctoring_status', 40)->nullable();
            });
        }

        if (!Schema::hasTable('exam_proctoring_events')) {
            Schema::create('exam_proctoring_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exam_id');
                $table->unsignedBigInteger('exam_user_id');
                $table->unsignedBigInteger('user_id');
                $table->string('event_type', 50);
                $table->string('message', 255)->nullable();
                $table->string('image_path', 255)->nullable();
                $table->timestamps();

                $table->index(['exam_user_id', 'event_type']);
                $table->index('exam_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('exams') && Schema::hasColumn('exams', 'is_proctored')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn(['is_proctored', 'proctoring_max_violations']);
            });
        }

        if (Schema::hasTable('exam_users') && Schema::hasColumn('exam_users', 'tab_switch_count')) {
            Schema::table('exam_users', function (Blueprint $table) {
                $table->dropColumn(['tab_switch_count', 'proctoring_status']);
            });
        }

        Schema::dropIfExists('exam_proctoring_events');
    }
};
