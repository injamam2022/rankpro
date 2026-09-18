<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mistake_input extends Model
{
    protected $fillable = [
        'name','description',
        'status','created','updated'
    ];
}
