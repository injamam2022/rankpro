<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = ['video', 'status'];


    public function descriptions()
    {
        return $this->hasMany(ScholarshipDescription::class, 'scholarship_id');
    }
}
