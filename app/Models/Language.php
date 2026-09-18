<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    protected $fillable = [
        'name',
        'description',
        'is_default',
        'status',
        'created_at',
        'updated_at',
    ];
}
