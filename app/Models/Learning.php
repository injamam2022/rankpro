<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Learning extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'status'];


    public function descriptions()
    {
        return $this->hasMany(LearningDescription::class, 'learning_id');
    }
}
