<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSeriesLocation extends Model
{
    use HasFactory;

    protected $fillable = ['test_series_id', 'location_name', 'status'];
}
