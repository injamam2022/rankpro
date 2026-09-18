<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankerRules extends Model
{
    use HasFactory;

    protected $fillable = ['uniq_id', 'language', 'rule', 'status'];
}
