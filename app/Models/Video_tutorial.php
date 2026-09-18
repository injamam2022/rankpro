<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Video_tutorial extends Model
{
    protected $fillable = [
        'page_name',
        'page_key',
        'name',
        'description',
        'video_link',
        'status',
        'created_at',
        'updated_at',
    ];
}
