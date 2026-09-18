<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadQuater extends Model
{
    use HasFactory;

    protected $fillable = ['video', 'status'];


    public function descriptions()
    {
        return $this->hasMany(HeadQuaterDescription::class, 'head_quater_id');
    }
}
