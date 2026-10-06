<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question_paper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProctoringPracticeExamSeeder extends Seeder
{
    public function run()
    {
        $paperQuery = Question_paper::query();
        if (Schema::hasColumn('question_papers', 'status')) {
            $paperQuery->where('status', 1);
        }
        $paper = $paperQuery->orderBy('id', 'desc')->first();
        if (!$paper) {
            $this->command?->error('No question paper found.');
            return;
        }

        $source = Exam::where('type', 1)->orderBy('id', 'desc')->first();
        $codeBase = (int) preg_replace('/\D+/', '', (string) (Exam::max('id') ?: 9000)) + 1000;

        $names = [
            'PROCTO EX 1',
            'PROCTO EX 2',
            'PROCTO EX 3',
        ];

        $studentIds = DB::table('users')
            ->where('email_id', 'exam.tester@rankpro.local')
            ->pluck('id')
            ->all();

        if (empty($studentIds)) {
            $studentIds = DB::table('users')->orderByDesc('id')->limit(1)->pluck('id')->all();
        }

        $batchIds = [];
        if (!empty($studentIds) && Schema::hasTable('batch_user')) {
            $batchIds = DB::table('batch_user')
                ->whereIn('user_id', $studentIds)
                ->pluck('batch_id')
                ->unique()
                ->values()
                ->all();
        }

        foreach ($names as $index => $name) {
            $payload = [
                'name' => $name,
                'type' => 1,
                'status' => 1,
                'is_deleted' => 0,
                'question_paper_id' => $paper->id,
                'exam_date' => date('Y-m-d'),
                'exam_time' => '00:00:00',
                'exam_end_date' => date('Y-m-d', strtotime('+14 days')),
                'exam_end_time' => '23:59:59',
                'is_proctored' => 1,
                'proctoring_max_violations' => 5,
                'total_time_for_exam' => 60,
                'exam_code' => (string) ($codeBase + $index + 1),
                'exam_instructions' => 'Proctored practice exam. Webcam monitoring is required. Time limit is 60 minutes.',
                'description' => 'Proctored practice exam for webcam testing.',
                'result_title' => $source->result_title ?? 'Result',
                'result_description' => $source->result_description ?? '',
                'result_declaration' => $source->result_declaration ?? '',
                'exam_logo' => $source->exam_logo ?? null,
                'landing_icon' => $source->landing_icon ?? null,
                'price' => $source->price ?? 0,
                'dis_price' => $source->dis_price ?? 0,
                'tax' => $source->tax ?? 0,
                'is_trending' => 0,
                'is_ended' => 0,
            ];

            $exam = Exam::where('name', $name)->where('is_deleted', 0)->first();
            if ($exam) {
                $exam->update($payload);
            } else {
                $exam = Exam::create($payload);
            }

            if (!empty($studentIds) && Schema::hasTable('exam_assignments')) {
                foreach ($studentIds as $userId) {
                    DB::table('exam_assignments')->updateOrInsert(
                        ['exam_id' => $exam->id, 'user_id' => $userId],
                        []
                    );
                }
            }

            if (!empty($batchIds) && Schema::hasTable('batch_exam')) {
                foreach ($batchIds as $batchId) {
                    DB::table('batch_exam')->updateOrInsert(
                        ['exam_id' => $exam->id, 'batch_id' => $batchId],
                        []
                    );
                }
            }

            $this->command?->info('Exam ready: '.$exam->name.' (ID '.$exam->id.')');
        }
    }
}
