<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankerFeedbacks extends Model
{
    use HasFactory;

    protected $fillable = ['ranker_id', 'user_id', 'rating', 'text', 'status','created_at','updated_at'];
}
