<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offline_exam_question extends Model
{

    protected $fillable = [
        'exam_id',
        'question_number',
        'question_text',
        'subject_id',
        'chapter_id',
        'topic_id',
        'sub_topic_id',
        'difficulty_level',
        'question_type_id',
        'answer_behavior_tag1',
        'answer_behavior_tag2',
        'answer_behavior_tag3',
        'answer_behavior_tag4',
        'video_link',
        'answer',
        'status',
        'created_at',
        'updated_at',
    ];
}
