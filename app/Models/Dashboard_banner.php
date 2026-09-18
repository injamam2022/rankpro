<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dashboard_banner extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'video_link',
        'status',
        'created_at',
        'updated_at',
    ];
}
