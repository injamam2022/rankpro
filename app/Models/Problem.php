<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'status',
        'created_at',
        'updated_at',
    ];
}
