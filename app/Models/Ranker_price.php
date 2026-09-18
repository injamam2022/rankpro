<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranker_price extends Model
{
    protected $fillable = [
        'title','description','price','dis_price','type','time','ranker_id',
        'status','created_at','updated_at'
    ];
}
