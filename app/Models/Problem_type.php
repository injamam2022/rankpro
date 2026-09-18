<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem_type extends Model
{

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];
}
