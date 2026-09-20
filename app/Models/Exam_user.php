<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam_user extends Model
{

    protected $fillable = [
        'user_id',
        'exam_id',
        'exam_type',
        'total_answer',
        'total_right_answer',
        'total_number',
        'question_number',
        'total_time',
        'percentage',
        'rank',
        'total_mark',
        'status',
        'tab_switch_count',
        'proctoring_status',
        'created_at',
        'updated_at',
    ];
}
