<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sub_topic extends Model
{
    protected $fillable = [
        'subject_id',
        'chapter_id',
        'topic_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
