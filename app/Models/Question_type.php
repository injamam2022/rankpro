<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question_type extends Model
{
    protected $fillable = [
        'name','description',
        'status','created_at','updated_at'
    ];
}
