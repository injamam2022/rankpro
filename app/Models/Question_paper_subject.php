<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_paper_subject extends Model
{
    protected $fillable = [
        'question_paper_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'sub_topic_id',
        'total_no_of_question',
        'status',
        'created_at',
        'updated_at',
    ];
}
