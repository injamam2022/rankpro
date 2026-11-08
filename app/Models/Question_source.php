<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Question_source extends Model
{
    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

    private static ?Collection $questionCounts = null;
    private static ?array $chapterMasterSetIds = null;
    private static ?Collection $majorityChapterBySet = null;

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'question_question_source',
            'question_source_id',
            'question_id'
        );
    }

    /**
     * Distinct live questions tagged to each question source.
     *
     * @return Collection<int, int>
     */
    public static function questionCounts(): Collection
    {
        if (self::$questionCounts instanceof Collection) {
            return self::$questionCounts;
        }

        $direct = DB::table('questions')
            ->selectRaw('question_source_id as source_id, id as question_id')
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->where('question_source_id', '>', 0);

        $tagged = $direct;
        if (Schema::hasTable('question_question_source')) {
            $pivot = DB::table('question_question_source as qqs')
                ->join('questions as q', 'q.id', '=', 'qqs.question_id')
                ->selectRaw('qqs.question_source_id as source_id, qqs.question_id as question_id')
                ->where('q.is_deleted', 0)
                ->where('q.status', 1);
            $tagged = $direct->union($pivot);
        }

        self::$questionCounts = DB::query()
            ->fromSub($tagged, 'tagged')
            ->selectRaw('source_id, COUNT(DISTINCT question_id) as total')
            ->groupBy('source_id')
            ->pluck('total', 'source_id')
            ->mapWithKeys(fn ($total, $id) => [(int) $id => (int) $total]);

        return self::$questionCounts;
    }

    /**
     * 45-question chapter sets used by Chapter Master (40–50 tagged questions).
     *
     * @return array<int>
     */
    public static function chapterMasterSetIds(): array
    {
        if (self::$chapterMasterSetIds !== null) {
            return self::$chapterMasterSetIds;
        }

        self::$chapterMasterSetIds = self::questionCounts()
            ->filter(fn ($total) => $total >= 40 && $total <= 50)
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        return self::$chapterMasterSetIds;
    }

    /**
     * Majority chapter for each 45-question set.
     *
     * @return Collection<int, int> set id => chapter id
     */
    public static function majorityChapterBySet(): Collection
    {
        if (self::$majorityChapterBySet instanceof Collection) {
            return self::$majorityChapterBySet;
        }

        $setIds = self::chapterMasterSetIds();
        if ($setIds === []) {
            self::$majorityChapterBySet = collect();
            return self::$majorityChapterBySet;
        }

        $direct = DB::table('questions')
            ->selectRaw('question_source_id as source_id, chapter_id, id as question_id')
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->whereNotNull('chapter_id')
            ->whereIn('question_source_id', $setIds);

        $tagged = $direct;
        if (Schema::hasTable('question_question_source')) {
            $pivot = DB::table('question_question_source as qqs')
                ->join('questions as q', 'q.id', '=', 'qqs.question_id')
                ->selectRaw('qqs.question_source_id as source_id, q.chapter_id, qqs.question_id as question_id')
                ->where('q.is_deleted', 0)
                ->where('q.status', 1)
                ->whereNotNull('q.chapter_id')
                ->whereIn('qqs.question_source_id', $setIds);
            $tagged = $direct->union($pivot);
        }

        $rows = DB::query()
            ->fromSub($tagged, 'tagged')
            ->selectRaw('source_id, chapter_id, COUNT(DISTINCT question_id) as total')
            ->groupBy('source_id', 'chapter_id')
            ->get();

        self::$majorityChapterBySet = $rows
            ->groupBy('source_id')
            ->map(function ($group) {
                return (int) $group->sortByDesc('total')->first()->chapter_id;
            });

        return self::$majorityChapterBySet;
    }

    /**
     * @return array<int>
     */
    public static function chapterMasterSetIdsForChapter(int $chapterId): array
    {
        return self::majorityChapterBySet()
            ->filter(fn ($mappedChapterId) => (int) $mappedChapterId === $chapterId)
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    /**
     * Question count shown on Chapter Master for each chapter (size of its 45-question sets).
     *
     * @return Collection<int, int> chapter id => total
     */
    public static function chapterMasterCountsByChapter(): Collection
    {
        $setCounts = self::questionCounts();
        $totals = [];
        foreach (self::majorityChapterBySet() as $setId => $chapterId) {
            $chapterId = (int) $chapterId;
            $totals[$chapterId] = ($totals[$chapterId] ?? 0) + (int) ($setCounts[(int) $setId] ?? 0);
        }

        return collect($totals);
    }

    /**
     * Count shown on Rank Chapter Master: 45 when this chapter has a tagged 45-question set.
     * Extra sets in the same chapter are not added together.
     */
    public static function chapterMasterDisplayCount(int $chapterId, int $perChapter = 45): int
    {
        $raw = (int) (self::chapterMasterCountsByChapter()[$chapterId] ?? 0);

        return $raw >= 40 ? $perChapter : 0;
    }
}
