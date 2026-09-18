<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cms_text extends Model
{
    protected $fillable = [
        'page_name','page_key',
        'banner_header','banner_description','banner_logo',
        'header','description',
        'status','created_at','updated_at'
    ];
}
