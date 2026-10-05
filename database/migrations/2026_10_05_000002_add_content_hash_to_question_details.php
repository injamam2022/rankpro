<?php

use App\Models\Question_detail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('question_details') && !Schema::hasColumn('question_details', 'content_hash')) {
            Schema::table('question_details', function (Blueprint $table) {
                $table->char('content_hash', 32)->nullable()->index();
            });
        }

        if (!Schema::hasTable('question_details') || !Schema::hasColumn('question_details', 'content_hash')) {
            return;
        }

        DB::table('question_details')
            ->select([
                'id',
                'question_text',
                'option1',
                'option2',
                'option3',
                'option4',
                'is_option1_image',
                'is_option2_image',
                'is_option3_image',
                'is_option4_image',
            ])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('question_details')->where('id', $row->id)->update([
                        'content_hash' => Question_detail::contentHash(
                            $row->question_text,
                            $row->option1,
                            $row->option2,
                            $row->option3,
                            $row->option4,
                            $row->is_option1_image,
                            $row->is_option2_image,
                            $row->is_option3_image,
                            $row->is_option4_image
                        ),
                    ]);
                }
            });
    }

    public function down()
    {
        if (Schema::hasTable('question_details') && Schema::hasColumn('question_details', 'content_hash')) {
            Schema::table('question_details', function (Blueprint $table) {
                $table->dropColumn('content_hash');
            });
        }
    }
};
