<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam_question extends Model
{

    protected $fillable = [
        'question_id',
        'exam_id',
        'exam_subject_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
