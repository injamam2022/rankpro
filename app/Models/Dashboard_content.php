<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dashboard_content extends Model
{
    protected $fillable = [
        'board',
        'leader_board',
        'status',
        'created_at',
        'updated_at',
    ];
}
