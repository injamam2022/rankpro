<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{

    protected $fillable = [
        'name',
        'key_name',
        'value',
        'status',
        'created_at',
        'updated_at',
    ];
}
