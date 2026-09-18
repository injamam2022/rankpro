<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offline_exam_question_result extends Model
{

    protected $fillable = [
        'user_id',
        'exam_id',
        'offline_exam_question_id',
        'offline_exam_user_id',
        'answer',
        'status',
        'created_at',
        'updated_at',
    ];
}
