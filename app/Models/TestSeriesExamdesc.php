<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSeriesExamdesc extends Model
{
    use HasFactory;

    protected $fillable = ['test_series_id', 'language', 'examdesc', 'status'];
}
