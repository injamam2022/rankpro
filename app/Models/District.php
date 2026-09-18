<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{

    protected $fillable = [
        'country_id',
        'state_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
