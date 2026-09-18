<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam_mistake_input extends Model
{

    protected $fillable = [
        'exam_id',
        'user_id',
        'exam_user_id',
        'question_id',
        'mistake_input_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
