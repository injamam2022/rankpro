<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'name','description','is_urgent',
        'status','created_at','updated_at'
    ];
}
