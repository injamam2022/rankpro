<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Question;
use App\Models\Question_detail;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Sub_topic;

class ExcelToDbImport implements ToCollection, WithHeadingRow
{
    protected $subject_id;
    protected $administrator_id;
    /** @var array<string,string> basename(lower) => stored filename */
    protected $imageMap;

    public $message = '';
    public $imported = 0;
    public $skipped = 0;
    public $errors = [];

    public function __construct($subject_id, $administrator_id = 0, array $imageMap = [])
    {
        $this->subject_id = (int) $subject_id;
        $this->administrator_id = (int) $administrator_id;
        $this->imageMap = $imageMap;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $raw) {
            $rowNum = $index + 2; // heading is row 1
            $val = $this->normalizeRow($raw);

            if ($this->isEmptyRow($val)) {
                continue;
            }

            $sl = $val['sl_no'] !== '' ? $val['sl_no'] : (string) $rowNum;

            try {
                $questionText = trim((string) ($val['question_text'] ?? ''));
                if ($questionText === '' && trim((string) ($val['question_image'] ?? '')) === '') {
                    $this->errors[] = "Row {$sl}: Question Text or Question Image is required.";
                    continue;
                }

                $chapterId = (int) ($val['chapter'] ?? 0);
                $topicId = (int) ($val['topic'] ?? 0);
                $subTopicId = (int) ($val['sub_topic'] ?? 0);
                $difficulty = (int) ($val['difficulty_level'] ?? 0);
                $answer = (int) ($val['answer'] ?? 0);

                if ($chapterId <= 0 || $topicId <= 0 || $subTopicId <= 0) {
                    $this->errors[] = "Row {$sl}: Chapter, Topic and Sub Topic IDs are required.";
                    continue;
                }

                if (!in_array($difficulty, [1, 2, 3], true)) {
                    $this->errors[] = "Row {$sl}: Difficulty Level must be 1 (Easy), 2 (Medium) or 3 (Hard).";
                    continue;
                }

                if (!in_array($answer, [1, 2, 3, 4], true)) {
                    $this->errors[] = "Row {$sl}: Answer must be 1, 2, 3 or 4.";
                    continue;
                }

                if (!$this->taxonomyBelongsToSubject($chapterId, $topicId, $subTopicId)) {
                    $this->errors[] = "Row {$sl}: Chapter/Topic/Sub Topic do not belong to the selected subject.";
                    continue;
                }

                $isOpt1 = $this->flag($val['is_option1_image'] ?? 0);
                $isOpt2 = $this->flag($val['is_option2_image'] ?? 0);
                $isOpt3 = $this->flag($val['is_option3_image'] ?? 0);
                $isOpt4 = $this->flag($val['is_option4_image'] ?? 0);

                $option1 = $this->resolveOption($val['option1'] ?? '', $isOpt1, $sl, 1);
                $option2 = $this->resolveOption($val['option2'] ?? '', $isOpt2, $sl, 2);
                $option3 = $this->resolveOption($val['option3'] ?? '', $isOpt3, $sl, 3);
                $option4 = $this->resolveOption($val['option4'] ?? '', $isOpt4, $sl, 4);

                if ($option1 === null || $option2 === null || $option3 === null || $option4 === null) {
                    continue;
                }

                $questionImage = $this->resolveImageName($val['question_image'] ?? '');
                if (trim((string) ($val['question_image'] ?? '')) !== '' && $questionImage === null) {
                    $this->errors[] = "Row {$sl}: Question image file not found in Image Zip (or uploads/question).";
                    continue;
                }

                $hash = Question_detail::contentHash(
                    $questionText,
                    $option1,
                    $option2,
                    $option3,
                    $option4,
                    $isOpt1,
                    $isOpt2,
                    $isOpt3,
                    $isOpt4
                );

                $duplicate = false;
                $activeQuestionIds = function ($query) {
                    $query->select('id')->from('questions')->where('is_deleted', 0);
                };

                if ($hash) {
                    $duplicate = Question_detail::where('content_hash', $hash)
                        ->where('language_id', 1)
                        ->whereIn('question_id', $activeQuestionIds)
                        ->exists();
                }

                if (!$duplicate && $questionText !== '') {
                    $duplicate = Question_detail::where('question_text', $questionText)
                        ->where('language_id', 1)
                        ->whereIn('question_id', $activeQuestionIds)
                        ->exists();
                }

                if ($duplicate) {
                    $this->skipped++;
                    $this->message = $this->message === '' ? (string) $sl : $this->message.', '.$sl;
                    continue;
                }

                DB::transaction(function () use ($val, $chapterId, $topicId, $subTopicId, $difficulty, $answer, $questionText, $questionImage, $option1, $option2, $option3, $option4, $isOpt1, $isOpt2, $isOpt3, $isOpt4) {
                    $sourceIds = array_values(array_unique(array_filter(array_map(
                        'intval',
                        preg_split('/[,\s]+/', (string) ($val['question_source'] ?? ''), -1, PREG_SPLIT_NO_EMPTY) ?: []
                    ))));
                    $question = Question::create([
                        'subject_id' => $this->subject_id,
                        'chapter_id' => $chapterId,
                        'source_id' => (int) ($val['source'] ?? 0),
                        'topic_id' => $topicId,
                        'sub_topic_id' => $subTopicId,
                        'difficulty_level' => $difficulty,
                        'is_premium' => $this->premiumFlag($val),
                        'question_type_id' => (int) ($val['question_type'] ?? 0),
                        'question_source_id' => $sourceIds[0] ?? 0,
                        'solution_video_link' => trim((string) ($val['solution_video_link'] ?? '')),
                        'answer' => $answer,
                        'administrator_id' => $this->administrator_id,
                        'is_deleted' => 0,
                        'status' => $this->flag($val['status'] ?? 1) ? 1 : 0,
                    ]);
                    $question->syncQuestionSources($sourceIds);

                    Question_detail::create([
                        'question_id' => $question->id,
                        'language_id' => 1,
                        'solution' => (string) ($val['solution'] ?? ''),
                        'question_text' => $questionText,
                        'question_image' => $questionImage ?: null,
                        'option1' => $option1,
                        'is_option1_image' => $isOpt1,
                        'option2' => $option2,
                        'is_option2_image' => $isOpt2,
                        'option3' => $option3,
                        'is_option3_image' => $isOpt3,
                        'option4' => $option4,
                        'is_option4_image' => $isOpt4,
                        'answer_behavior_tag1' => (int) ($val['ans_behavior_tag_1'] ?? 0),
                        'answer_behavior_tag2' => (int) ($val['ans_behavior_tag_2'] ?? 0),
                        'answer_behavior_tag3' => (int) ($val['ans_behavior_tag_3'] ?? 0),
                        'answer_behavior_tag4' => (int) ($val['ans_behavior_tag_4'] ?? 0),
                        'status' => $this->flag($val['status'] ?? 1) ? 1 : 0,
                    ]);
                });

                $this->imported++;
            } catch (\Throwable $e) {
                $this->errors[] = "Row {$sl}: ".$e->getMessage();
            }
        }

        Question_detail::forgetDuplicateCache();
    }

    protected function normalizeRow($raw): array
    {
        $map = [];
        foreach ($raw as $key => $value) {
            $normalized = $this->normalizeKey($key);
            if ($normalized === '') {
                continue;
            }
            $map[$normalized] = is_string($value) ? trim($value) : $value;
        }

        // Aliases for common header variants
        if (!isset($map['sl_no']) && isset($map['slno'])) {
            $map['sl_no'] = $map['slno'];
        }
        if (!isset($map['sub_topic']) && isset($map['subtopic'])) {
            $map['sub_topic'] = $map['subtopic'];
        }
        if (!isset($map['is_premium']) && isset($map['premium'])) {
            $map['is_premium'] = $map['premium'];
        }
        if (!isset($map['is_premium']) && isset($map['question_segment'])) {
            $map['is_premium'] = $map['question_segment'];
        }

        return $map;
    }

    protected function normalizeKey($key): string
    {
        $key = strtolower(trim((string) $key));
        $key = preg_replace('/[^a-z0-9]+/', '_', $key);
        return trim($key, '_');
    }

    protected function isEmptyRow(array $val): bool
    {
        foreach (['question_text', 'question_image', 'option1', 'option2', 'chapter', 'topic'] as $field) {
            if (isset($val[$field]) && trim((string) $val[$field]) !== '') {
                return false;
            }
        }
        return true;
    }

    protected function flag($value): int
    {
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }
        $value = strtolower(trim((string) $value));
        return in_array($value, ['1', 'yes', 'y', 'true'], true) ? 1 : 0;
    }

    /** 1 = Course Packages premium bank; 0 = Custom Test (default). */
    protected function premiumFlag(array $val): int
    {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('questions', 'is_premium')) {
            return 0;
        }

        $raw = $val['is_premium'] ?? $val['premium'] ?? 0;
        if (is_bool($raw)) {
            return $raw ? 1 : 0;
        }
        $value = strtolower(trim((string) $raw));
        if (in_array($value, ['1', 'yes', 'y', 'true', 'premium', 'course_package', 'course packages', 'chapter_master'], true)) {
            return 1;
        }

        return 0;
    }

    protected function taxonomyBelongsToSubject(int $chapterId, int $topicId, int $subTopicId): bool
    {
        $chapter = Chapter::where('id', $chapterId)
            ->where('subject_id', $this->subject_id)
            ->where('status', 1)
            ->first();
        if (!$chapter) {
            return false;
        }

        $topic = Topic::where('id', $topicId)
            ->where('chapter_id', $chapterId)
            ->where('status', 1)
            ->first();
        if (!$topic) {
            return false;
        }

        $sub = Sub_topic::where('id', $subTopicId)
            ->where('topic_id', $topicId)
            ->where('status', 1)
            ->first();

        return (bool) $sub;
    }

    protected function resolveOption($value, int $isImage, $sl, int $n): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            $this->errors[] = "Row {$sl}: Option{$n} is required.";
            return null;
        }

        if ($isImage) {
            $resolved = $this->resolveImageName($value);
            if ($resolved === null) {
                $this->errors[] = "Row {$sl}: Option{$n} image \"{$value}\" not found in Image Zip (or uploads/question).";
                return null;
            }
            return $resolved;
        }

        return $value;
    }

    /**
     * Map spreadsheet image name to stored file under public/uploads/question.
     * Prefer zip extract map; fall back to file already present on disk.
     */
    protected function resolveImageName($name): ?string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return null;
        }

        $base = basename(str_replace('\\', '/', $name));
        $key = strtolower($base);

        if (isset($this->imageMap[$key])) {
            return $this->imageMap[$key];
        }

        $path = public_path('uploads/question/'.$base);
        if (is_file($path)) {
            return $base;
        }

        return null;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getSummary(): array
    {
        return [
            'imported' => $this->imported,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
            'duplicates' => $this->message,
        ];
    }
}
