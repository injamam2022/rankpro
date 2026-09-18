<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_source extends Model
{
    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
