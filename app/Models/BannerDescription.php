<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerDescription extends Model
{
    use HasFactory;

    protected $fillable = ['banner_id', 'language', 'text', 'status'];


    public function banner()
    {
        return $this->belongsTo(Banner::class, 'banner_id');
    }
}
