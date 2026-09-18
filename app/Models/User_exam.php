<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_exam extends Model
{

    protected $fillable = [
        'user_id',
        'exam_id',
        'total_time',
        'total_answer',
        'total_number',
        'question_number',
        'status',
        'created_at',
        'updated_at',
    ];
}
