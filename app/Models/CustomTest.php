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

    public function examUser()
    {
        return $this->belongsTo(Exam_user::class, 'exam_user_id');
    }
}
