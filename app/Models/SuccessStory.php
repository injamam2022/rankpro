<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'image', 'status'];

    public function descriptions()
    {
        return $this->hasMany(SuccessStoryDescription::class, 'success_story_id');
    }
}
