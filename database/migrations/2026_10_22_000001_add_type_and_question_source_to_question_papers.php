<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('question_papers')) {
            return;
        }

        if (! Schema::hasColumn('question_papers', 'type')) {
            Schema::table('question_papers', function (Blueprint $table) {
                $table->unsignedTinyInteger('type')->default(1);
            });
        }
        if (! Schema::hasColumn('question_papers', 'question_source_id')) {
            Schema::table('question_papers', function (Blueprint $table) {
                $table->unsignedInteger('question_source_id')->nullable();
            });
        }
    }

    public function down()
    {
        if (! Schema::hasTable('question_papers')) {
            return;
        }

        if (Schema::hasColumn('question_papers', 'question_source_id')) {
            Schema::table('question_papers', function (Blueprint $table) {
                $table->dropColumn('question_source_id');
            });
        }
        if (Schema::hasColumn('question_papers', 'type')) {
            Schema::table('question_papers', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
