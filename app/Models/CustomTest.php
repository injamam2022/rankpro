<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTest extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id',
        'name',
        'question_count',
        'duration_minutes',
        'marks_per_question',
        'negative_marking',
        'negative_marks',
        'difficulty',
        'question_paper_id',
        'exam_id',
        'exam_user_id',
        'status',
        'selection',
    ];

    protected $casts = [
        'selection' => 'array',
        'negative_marking' => 'boolean',
        'marks_per_question' => 'float',
        'negative_marks' => 'float',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function examUser()
    {
        return $this->belongsTo(Exam_user::class, 'exam_user_id');
    }

    public static function examIdQuery()
    {
        return static::query()->whereNotNull('exam_id')->select('exam_id');
    }

    public static function questionPaperIdQuery()
    {
        // Only hide the paper that was created with the custom test.
        // A later admin paper can reuse the same id after the original row is removed,
        // and that paper must still appear in Question Bank.
        return static::query()
            ->join('question_papers as qp', 'qp.id', '=', 'custom_tests.question_paper_id')
            ->whereNotNull('custom_tests.question_paper_id')
            ->whereRaw('ABS(TIMESTAMPDIFF(SECOND, qp.created_at, custom_tests.created_at)) <= 120')
            ->select('custom_tests.question_paper_id');
    }
}
