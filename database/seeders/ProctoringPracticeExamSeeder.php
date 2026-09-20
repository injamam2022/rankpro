<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question_paper;
use Illuminate\Database\Seeder;
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
        $codeBase = (int) (Exam::max('exam_code') ?: 9000);
        $names = [
            'Proctoring Practice A',
            'Proctoring Practice B',
            'Proctoring Practice C',
        ];

        foreach ($names as $index => $name) {
            $payload = [
                'name' => $name,
                'type' => 1,
                'status' => 1,
                'is_deleted' => 0,
                'question_paper_id' => $paper->id,
                'exam_date' => date('Y-m-d', strtotime('+14 days')),
                'exam_time' => '09:00:00',
                'is_proctored' => 1,
                'proctoring_max_violations' => 5,
                'total_time_for_exam' => 60,
                'exam_code' => $codeBase + $index + 1,
                'exam_instructions' => 'Practice exam for webcam proctoring. Time limit is 60 minutes.',
                'description' => 'Fresh proctored practice exam.',
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

            $this->command?->info('Exam ready: '.$exam->name.' (ID '.$exam->id.')');
        }
    }
}
