<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestDescription extends Model
{
    use HasFactory;

    protected $fillable = ['interest_id', 'language', 'text', 'status'];


    public function interest()
    {
        return $this->belongsTo(Interest::class, 'interest_id');
    }
}
