<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['name', 'status'];

    public function students()
    {
        return $this->belongsToMany(User::class, 'batch_user');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'batch_exam');
    }
}
