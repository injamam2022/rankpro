<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'subject_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
