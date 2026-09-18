<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'status'];


    public function descriptions()
    {
        return $this->hasMany(BannerDescription::class, 'banner_id');
    }
}
