<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('question_question_source')) {
            Schema::create('question_question_source', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('question_id')->index();
                $table->unsignedInteger('question_source_id')->index();
                $table->timestamps();
                $table->unique(['question_id', 'question_source_id'], 'question_source_unique');
            });
        }

        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'question_source_id')) {
            $now = now();
            DB::table('questions')
                ->select('id', 'question_source_id')
                ->whereNotNull('question_source_id')
                ->where('question_source_id', '>', 0)
                ->orderBy('id')
                ->chunkById(500, function ($rows) use ($now) {
                    $insert = [];
                    foreach ($rows as $row) {
                        $insert[] = [
                            'question_id' => $row->id,
                            'question_source_id' => $row->question_source_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    if ($insert) {
                        DB::table('question_question_source')->insertOrIgnore($insert);
                    }
                });
        }
    }

    public function down()
    {
        Schema::dropIfExists('question_question_source');
    }
};
