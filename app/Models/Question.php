<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Question extends Model
{

    protected $fillable = [
        'subject_id',
        'chapter_id',
        'source_id',
        'topic_id',
        'sub_topic_id',
        'question_type_id',
        'difficulty_level',
        'is_premium',
        'question_source_id',
        'solution_video_link',
        'answer',
        'administrator_id',
        'is_deleted',
        'status',
        'created_at',
        'updated_at',
    ];

    public function scopeNonPremium(Builder $query): Builder
    {
        return self::applyNonPremium($query);
    }

    public static function applyNonPremium(Builder $query, string $column = 'is_premium'): Builder
    {
        if (! Schema::hasColumn('questions', 'is_premium')) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($column) {
            $q->where($column, 0)->orWhereNull($column);
        });
    }

    public function questionSources()
    {
        return $this->belongsToMany(
            Question_source::class,
            'question_question_source',
            'question_id',
            'question_source_id'
        );
    }

    public function selectedQuestionSourceIds(): array
    {
        if (Schema::hasTable('question_question_source')) {
            $ids = $this->questionSources()->pluck('question_sources.id')->map(fn ($id) => (int) $id)->all();
            if ($ids) {
                return array_values(array_unique($ids));
            }
        }

        return $this->question_source_id ? [(int) $this->question_source_id] : [];
    }

    public function syncQuestionSources(array $ids): void
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if (Schema::hasTable('question_question_source')) {
            $this->questionSources()->sync($ids);
        }
        $first = $ids[0] ?? 0;
        if ((int) $this->question_source_id !== $first) {
            $this->update(['question_source_id' => $first]);
        }
    }

    public function belongsToQuestionSource(int $sourceId): bool
    {
        if ((int) $this->question_source_id === $sourceId) {
            return true;
        }
        if (! Schema::hasTable('question_question_source')) {
            return false;
        }

        return $this->questionSources()->where('question_sources.id', $sourceId)->exists();
    }

    public static function constrainBySource(Builder $query, int $sourceId, string $idColumn = 'questions.id', string $sourceColumn = 'questions.question_source_id'): Builder
    {
        return $query->where(function ($q) use ($sourceId, $idColumn, $sourceColumn) {
            $q->where($sourceColumn, $sourceId);
            if (Schema::hasTable('question_question_source')) {
                $q->orWhereExists(function ($sub) use ($sourceId, $idColumn) {
                    $sub->selectRaw('1')
                        ->from('question_question_source')
                        ->whereColumn('question_question_source.question_id', $idColumn)
                        ->where('question_question_source.question_source_id', $sourceId);
                });
            }
        });
    }
}
