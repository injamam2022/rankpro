<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chapters') || Schema::hasColumn('chapters', 'class_level')) {
            return;
        }

        Schema::table('chapters', function (Blueprint $table) {
            $table->unsignedTinyInteger('class_level')->default(0)->after('subject_id');
            $table->index(['subject_id', 'class_level']);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('chapters') || ! Schema::hasColumn('chapters', 'class_level')) {
            return;
        }

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropIndex(['subject_id', 'class_level']);
            $table->dropColumn('class_level');
        });
    }
};
