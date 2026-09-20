<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam_proctoring_event extends Model
{
    protected $table = 'exam_proctoring_events';

    protected $fillable = [
        'exam_id',
        'exam_user_id',
        'user_id',
        'event_type',
        'message',
        'image_path',
        'created_at',
        'updated_at',
    ];
}
