<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    protected $fillable = [
        'route_name',
        'title',
        'description',
        'keywords',
        'robots',
        'og_image',
        'schema_json',
    ];
}
