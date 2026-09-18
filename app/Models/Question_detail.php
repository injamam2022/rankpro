<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_detail extends Model
{
    protected $fillable = [
        'question_id',
        'language_id',
        'solution',
        'question_source_id',
        'question_text',
        'question_image',
        'option1',
        'is_option1_image',
        'option2',
        'is_option2_image',
        'option3',
        'is_option3_image',
        'option4',
        'is_option4_image',
        'answer_behavior_tag1',
        'answer_behavior_tag2',
        'answer_behavior_tag3',
        'answer_behavior_tag4',
        'status',
        'created_at',
        'updated_at',
    ];
}
