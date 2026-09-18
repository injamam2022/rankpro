<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStoryDescription extends Model
{
    use HasFactory;

    protected $fillable = ['success_story_id', 'language', 'text', 'status'];


    public function successStory()
    {
        return $this->belongsTo(SuccessStory::class, 'success_story_id');
    }
}
