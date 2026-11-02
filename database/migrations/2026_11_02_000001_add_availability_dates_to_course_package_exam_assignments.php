<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_package_exam_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('course_package_exam_assignments', 'available_from')) {
                $table->dateTime('available_from')->nullable()->after('question_paper_id');
            }
            if (!Schema::hasColumn('course_package_exam_assignments', 'available_until')) {
                $table->dateTime('available_until')->nullable()->after('available_from');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_package_exam_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('course_package_exam_assignments', 'available_until')) {
                $table->dropColumn('available_until');
            }
            if (Schema::hasColumn('course_package_exam_assignments', 'available_from')) {
                $table->dropColumn('available_from');
            }
        });
    }
};
