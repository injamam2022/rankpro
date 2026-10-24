<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QuestionPaperMixService
{
    public const EASY = 1;
    public const MEDIUM = 2;
    public const HARD = 3;

    /**
     * Largest-remainder split so counts always sum to $total.
     *
     * @param  array<string,int|float>  $weights
     * @return array<string,int>
     */
    public function splitByWeight(int $total, array $weights): array
    {
        $keys = array_keys($weights);
        $out = array_fill_keys($keys, 0);

        if ($total <= 0 || $keys === []) {
            return $out;
        }

        $sum = 0;
        foreach ($weights as $w) {
            $sum += max(0, (float) $w);
        }

        if ($sum <= 0) {
            $out[$keys[0]] = $total;

            return $out;
        }

        $frac = [];
        foreach ($weights as $key => $w) {
            $exact = $total * (max(0, (float) $w) / $sum);
            $out[$key] = (int) floor($exact);
            $frac[$key] = $exact - $out[$key];
        }

        $remain = $total - array_sum($out);
        arsort($frac);
        foreach (array_keys($frac) as $key) {
            if ($remain <= 0) {
                break;
            }
            $out[$key]++;
            $remain--;
        }

        return $out;
    }

    /**
     * @param  array{hard:int|float,medium:int|float,easy:int|float}  $percents
     * @return array{hard:int,medium:int,easy:int}
     */
    public function difficultyCounts(int $total, array $percents): array
    {
        $split = $this->splitByWeight($total, [
            'hard' => (float) ($percents['hard'] ?? 0),
            'medium' => (float) ($percents['medium'] ?? 0),
            'easy' => (float) ($percents['easy'] ?? 0),
        ]);

        return [
            'hard' => $split['hard'] ?? 0,
            'medium' => $split['medium'] ?? 0,
            'easy' => $split['easy'] ?? 0,
        ];
    }

    /**
     * @param  array<int,array<string,mixed>>  $sections
     */
    public function activePool(array $sections = [], ?int $lockSubjectId = null, ?int $sourceId = null): Collection
    {
        $query = $this->baseQuery($lockSubjectId, $sourceId);

        $usable = array_values(array_filter($sections, function ($row) {
            return ! empty($row['subject_id']);
        }));

        if ($usable) {
            $query->where(function (Builder $outer) use ($usable) {
                foreach ($usable as $i => $row) {
                    $outer->{$i === 0 ? 'where' : 'orWhere'}(function (Builder $q) use ($row) {
                        $q->where('subject_id', (int) $row['subject_id']);
                        if (! empty($row['chapter_id'])) {
                            $q->where('chapter_id', (int) $row['chapter_id']);
                        }
                        if (! empty($row['topic_id'])) {
                            $q->where('topic_id', (int) $row['topic_id']);
                        }
                        if (! empty($row['sub_topic_id'])) {
                            $q->where('sub_topic_id', (int) $row['sub_topic_id']);
                        }
                    });
                }
            });
        }

        return $query->get(['id', 'subject_id', 'chapter_id', 'topic_id', 'sub_topic_id', 'question_type_id', 'difficulty_level']);
    }

    /**
     * Auto type counts: difficulty mix is the driver. Each type's share is how
     * many of that type exist in the wanted difficulty buckets of the pool.
     *
     * @param  array<int,int>  $typeIds
     * @param  array{hard:int,medium:int,easy:int}  $diffCounts
     * @return array<int,int>  type_id => count
     */
    public function autoTypeCounts(Collection $pool, array $diffCounts, array $typeIds): array
    {
        $typeIds = array_values(array_unique(array_map('intval', $typeIds)));
        $out = array_fill_keys($typeIds, 0);
        $total = (int) array_sum($diffCounts);
        if ($total <= 0 || $typeIds === []) {
            return $out;
        }

        $map = [
            'easy' => self::EASY,
            'medium' => self::MEDIUM,
            'hard' => self::HARD,
        ];

        $scores = array_fill_keys($typeIds, 0.0);

        foreach ($map as $key => $level) {
            $want = (int) ($diffCounts[$key] ?? 0);
            if ($want <= 0) {
                continue;
            }
            $bucket = $pool->where('difficulty_level', $level);
            $bucketSize = $bucket->count();
            if ($bucketSize === 0) {
                continue;
            }
            foreach ($typeIds as $typeId) {
                $inType = $bucket->where('question_type_id', $typeId)->count();
                $scores[$typeId] += $want * ($inType / $bucketSize);
            }
        }

        if (array_sum($scores) <= 0) {
            $fallback = [];
            foreach ($typeIds as $typeId) {
                $fallback[$typeId] = $pool->where('question_type_id', $typeId)->count();
            }
            if (array_sum($fallback) <= 0) {
                return $out;
            }

            return $this->splitByWeight($total, $fallback);
        }

        return $this->splitByWeight($total, $scores);
    }

    /**
     * @param  array<int,array<string,mixed>>  $sections
     * @param  array{hard:int|float,medium:int|float,easy:int|float}  $percents
     * @param  array<int,int>  $typeIds
     */
    public function preview(int $total, array $percents, array $sections, array $typeIds, ?int $lockSubjectId = null, ?int $sourceId = null, bool $ignoreDifficulty = false): array
    {
        $pool = $this->activePool($sections, $lockSubjectId, $sourceId);
        $diffCounts = $ignoreDifficulty
            ? ['hard' => 0, 'medium' => 0, 'easy' => 0]
            : $this->difficultyCounts($total, $percents);
        $typeCounts = $this->autoTypeCounts($pool, $diffCounts, $typeIds);

        $byDiff = [
            'easy' => $pool->where('difficulty_level', self::EASY)->count(),
            'medium' => $pool->where('difficulty_level', self::MEDIUM)->count(),
            'hard' => $pool->where('difficulty_level', self::HARD)->count(),
        ];

        $types = [];
        foreach ($typeIds as $typeId) {
            $typeId = (int) $typeId;
            $ofType = $pool->where('question_type_id', $typeId);
            $types[] = [
                'id' => $typeId,
                'count' => (int) ($typeCounts[$typeId] ?? 0),
                'pool' => [
                    'easy' => $ofType->where('difficulty_level', self::EASY)->count(),
                    'medium' => $ofType->where('difficulty_level', self::MEDIUM)->count(),
                    'hard' => $ofType->where('difficulty_level', self::HARD)->count(),
                    'total' => $ofType->count(),
                ],
            ];
        }

        $warnings = [];
        if ($total > 0 && $pool->count() < $total) {
            $warnings[] = 'Only '.$pool->count().' matching questions in the pool (need '.$total.').';
        }
        if (! $ignoreDifficulty) {
            foreach (['hard' => 'Hard', 'medium' => 'Medium', 'easy' => 'Easy'] as $key => $label) {
                if ($diffCounts[$key] > $byDiff[$key]) {
                    $warnings[] = $label.' needs '.$diffCounts[$key].' but the pool has '.$byDiff[$key].'. Extra will come from other levels.';
                }
            }
        }

        return [
            'total' => $total,
            'pool_total' => $pool->count(),
            'difficulty' => [
                'hard' => ['percent' => (float) ($percents['hard'] ?? 0), 'count' => $diffCounts['hard'], 'pool' => $byDiff['hard']],
                'medium' => ['percent' => (float) ($percents['medium'] ?? 0), 'count' => $diffCounts['medium'], 'pool' => $byDiff['medium']],
                'easy' => ['percent' => (float) ($percents['easy'] ?? 0), 'count' => $diffCounts['easy'], 'pool' => $byDiff['easy']],
            ],
            'types' => $types,
            'warnings' => $warnings,
        ];
    }

    /**
     * Pick question ids for each subject row using difficulty first, then type mix.
     *
     * @param  array<int|string,array<string,mixed>>  $sections
     * @param  array{hard:int|float,medium:int|float,easy:int|float}  $percents
     * @param  array<int,int>  $typeCounts  type_id => count (or % — normalised by weight)
     * @return array{picked: array<int,array{question_id:int,section_key:int|string}>, shortfall:int, notes:array<int,string>}
     */
    public function pick(array $sections, array $percents, array $typeCounts, ?int $lockSubjectId = null, ?int $sourceId = null): array
    {
        $picked = [];
        $used = [];
        $notes = [];
        $shortfall = 0;

        $diffOrder = ['hard' => self::HARD, 'medium' => self::MEDIUM, 'easy' => self::EASY];

        foreach ($sections as $sectionKey => $section) {
            $want = (int) ($section['total_no_of_question'] ?? 0);
            if ($want <= 0 || empty($section['subject_id'])) {
                continue;
            }

            $before = count($picked);
            $diffWant = $this->difficultyCounts($want, $percents);

            foreach ($diffOrder as $diffKey => $level) {
                $dWant = (int) ($diffWant[$diffKey] ?? 0);
                if ($dWant <= 0) {
                    continue;
                }
                $ids = $this->takeFromPool($section, $level, null, $dWant, $used, $lockSubjectId, $sourceId);
                $this->appendPicked($picked, $used, $ids, $sectionKey);
            }

            $have = count($picked) - $before;
            if ($have < $want) {
                foreach ($diffOrder as $level) {
                    if ($have >= $want) {
                        break;
                    }
                    $ids = $this->takeFromPool($section, $level, null, $want - $have, $used, $lockSubjectId, $sourceId);
                    $have += $this->appendPicked($picked, $used, $ids, $sectionKey);
                }
            }

            if ($have < $want) {
                $ids = $this->takeFromPool($section, null, null, $want - $have, $used, $lockSubjectId, $sourceId);
                $have += $this->appendPicked($picked, $used, $ids, $sectionKey);
            }

            $missing = $want - $have;
            if ($missing > 0) {
                $shortfall += $missing;
                $notes[] = 'Subject '.$section['subject_id'].' is short by '.$missing.' question(s).';
            }
        }

        return [
            'picked' => $picked,
            'shortfall' => $shortfall,
            'notes' => $notes,
            'auto_sections' => [],
        ];
    }

    /**
     * Type=2: pick from one question source. Subject rows are optional.
     * With no subject rows, take $totalWanted at random and group by subject.
     *
     * @param  array<int|string,array<string,mixed>>  $sections
     * @return array{picked: array<int,array{question_id:int,section_key:int|string}>, shortfall:int, notes:array<int,string>, auto_sections: array<int|string,array<string,mixed>>}
     */
    public function pickFromSource(array $sections, int $sourceId, int $totalWanted, ?int $lockSubjectId = null): array
    {
        $usable = [];
        foreach ($sections as $key => $section) {
            if (empty($section['subject_id']) || (int) ($section['total_no_of_question'] ?? 0) <= 0) {
                continue;
            }
            $usable[$key] = $section;
        }

        if ($usable !== []) {
            $picked = [];
            $used = [];
            $notes = [];
            $shortfall = 0;

            foreach ($usable as $sectionKey => $section) {
                $want = (int) $section['total_no_of_question'];
                $ids = $this->takeFromPool($section, null, null, $want, $used, $lockSubjectId, $sourceId);
                $have = $this->appendPicked($picked, $used, $ids, $sectionKey);
                $missing = $want - $have;
                if ($missing > 0) {
                    $shortfall += $missing;
                    $notes[] = 'Subject '.$section['subject_id'].' is short by '.$missing.' question(s) in this source.';
                }
            }

            return [
                'picked' => $picked,
                'shortfall' => $shortfall,
                'notes' => $notes,
                'auto_sections' => [],
            ];
        }

        $questions = $this->baseQuery($lockSubjectId, $sourceId)
            ->inRandomOrder()
            ->limit(max(0, $totalWanted))
            ->get(['id', 'subject_id']);

        $shortfall = max(0, $totalWanted - $questions->count());
        $picked = [];
        $auto = [];
        foreach ($questions->groupBy('subject_id') as $subjectId => $group) {
            $key = 'src_'.(int) $subjectId;
            $auto[$key] = [
                'subject_id' => (int) $subjectId,
                'chapter_id' => null,
                'topic_id' => null,
                'sub_topic_id' => null,
                'total_no_of_question' => $group->count(),
            ];
            foreach ($group as $question) {
                $picked[] = ['question_id' => (int) $question->id, 'section_key' => $key];
            }
        }

        $notes = [];
        if ($shortfall > 0) {
            $notes[] = 'This question source has '.$questions->count().' usable question(s); '.$totalWanted.' were requested.';
        }

        return [
            'picked' => $picked,
            'shortfall' => $shortfall,
            'notes' => $notes,
            'auto_sections' => $auto,
        ];
    }

    /**
     * @param  array<int,int>  $used
     * @param  array<int,int>  $ids
     */
    private function appendPicked(array &$picked, array &$used, array $ids, $sectionKey): int
    {
        foreach ($ids as $id) {
            $picked[] = ['question_id' => (int) $id, 'section_key' => $sectionKey];
            $used[] = (int) $id;
        }

        return count($ids);
    }

    /**
     * @param  array<int,int>  $used
     * @return array<int,int>
     */
    private function takeFromPool(array $section, ?int $level, ?int $typeId, int $take, array $used, ?int $lockSubjectId, ?int $sourceId = null): array
    {
        if ($take <= 0) {
            return [];
        }

        $query = $this->baseQuery($lockSubjectId, $sourceId)
            ->where('subject_id', (int) $section['subject_id']);

        if (! empty($section['chapter_id'])) {
            $query->where('chapter_id', (int) $section['chapter_id']);
        }
        if (! empty($section['topic_id'])) {
            $query->where('topic_id', (int) $section['topic_id']);
        }
        if (! empty($section['sub_topic_id'])) {
            $query->where('sub_topic_id', (int) $section['sub_topic_id']);
        }
        if ($level !== null) {
            $query->where('difficulty_level', $level);
        }
        if ($used) {
            $query->whereNotIn('id', $used);
        }

        return $query->inRandomOrder()->limit($take)->pluck('id')->map(fn ($id) => (int) $id)->all();
    }

    private function baseQuery(?int $lockSubjectId, ?int $sourceId = null): Builder
    {
        $query = Question::query()
            ->where('status', 1)
            ->where(function (Builder $q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            });

        if ($lockSubjectId) {
            $query->where('subject_id', $lockSubjectId);
        }
        if ($sourceId) {
            Question::constrainBySource($query, $sourceId, 'questions.id', 'questions.question_source_id');
        }

        return $query;
    }
}
