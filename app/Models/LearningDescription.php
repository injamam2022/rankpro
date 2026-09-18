<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningDescription extends Model
{
    use HasFactory;

    protected $fillable = ['learning_id', 'language', 'text', 'status'];


    public function learning()
    {
        return $this->belongsTo(Learning::class, 'learning_id');
    }
}
