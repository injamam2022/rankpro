<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskedQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['uniq_id', 'language', 'type', 'text', 'text2', 'status'];

}
