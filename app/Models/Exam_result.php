<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam_result extends Model
{

    protected $fillable = [
        'exam_id',
        'exam_user_id',
        'user_id',
        'question_id',
        'exam_question_id',
        'answer',
        'result',
        'time',
        'type',
        'reported',
        'review_later',
        'status',
        'created_at',
        'updated_at',
    ];
}
