<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'name',
        'key_name',
        'type',
        'status',
        'created_at',
        'updated_at',
    ];
}
