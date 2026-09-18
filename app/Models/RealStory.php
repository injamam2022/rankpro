<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealStory extends Model
{
    use HasFactory;

    protected $fillable = ['uniq_id', 'language', 'text', 'status'];
}
