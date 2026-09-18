<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $fillable = [
        'subject_id',
        'chapter_id',
        'source_id',
        'topic_id',
        'sub_topic_id',
        'question_type_id',
        'difficulty_level',
        'question_source_id',
        'solution_video_link',
        'answer',
        'administrator_id',
        'is_deleted',
        'status',
        'created_at',
        'updated_at',
    ];
}
