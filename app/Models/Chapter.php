<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'subject_id',
        'class_level',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
