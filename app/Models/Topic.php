<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'subject_id',
        'chapter_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
