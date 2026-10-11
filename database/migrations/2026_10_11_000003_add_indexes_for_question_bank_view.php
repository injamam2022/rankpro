<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndex('question_details', 'question_details_question_id_language_id_index', function (Blueprint $table) {
            $table->index(['question_id', 'language_id'], 'question_details_question_id_language_id_index');
        });

        if (Schema::hasTable('question_details') && !$this->hasIndex('question_details', 'question_details_question_text_fulltext')) {
            try {
                DB::statement('ALTER TABLE question_details ADD FULLTEXT question_details_question_text_fulltext (question_text)');
            } catch (\Throwable $e) {
                // FULLTEXT is optional; ID indexes already make the view page fast.
            }
        }

        $this->addIndex('question_paper_questions', 'question_paper_questions_paper_id_index', function (Blueprint $table) {
            $table->index('question_paper_id', 'question_paper_questions_paper_id_index');
        });

        $this->addIndex('question_paper_questions', 'question_paper_questions_question_id_index', function (Blueprint $table) {
            $table->index('question_id', 'question_paper_questions_question_id_index');
        });
    }

    public function down(): void
    {
        $this->dropIndexIfExists('question_details', 'question_details_question_id_language_id_index');
        $this->dropIndexIfExists('question_details', 'question_details_question_text_fulltext');
        $this->dropIndexIfExists('question_paper_questions', 'question_paper_questions_paper_id_index');
        $this->dropIndexIfExists('question_paper_questions', 'question_paper_questions_question_id_index');
    }

    private function hasIndex(string $table, string $name): bool
    {
        if (!Schema::hasTable($table)) {
            return true;
        }
        $rows = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$name]);
        return !empty($rows);
    }

    private function addIndex(string $table, string $name, callable $callback): void
    {
        if (!Schema::hasTable($table) || $this->hasIndex($table, $name)) {
            return;
        }
        Schema::table($table, $callback);
    }

    private function dropIndexIfExists(string $table, string $name): void
    {
        if (!Schema::hasTable($table) || !$this->hasIndex($table, $name)) {
            return;
        }
        try {
            Schema::table($table, function (Blueprint $table) use ($name) {
                $table->dropIndex($name);
            });
        } catch (\Throwable $e) {
            try {
                DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$name}`");
            } catch (\Throwable $ignored) {
            }
        }
    }
};
