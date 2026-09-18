<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $fillable = [
        'location_id',
        'page_type',
        'name',
        'email',
        'mobile_number',
        'message',
        'status',
        'created_at',
        'updated_at',
    ];
}
