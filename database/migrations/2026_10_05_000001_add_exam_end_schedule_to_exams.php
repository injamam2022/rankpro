<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('exams') && !Schema::hasColumn('exams', 'exam_end_date')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->date('exam_end_date')->nullable()->after('exam_time');
                $table->time('exam_end_time')->nullable()->after('exam_end_date');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('exams') && Schema::hasColumn('exams', 'exam_end_date')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn(['exam_end_date', 'exam_end_time']);
            });
        }
    }
};
