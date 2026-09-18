<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSeriesDescription extends Model
{
    use HasFactory;

    protected $fillable = ['test_series_id', 'language', 'text', 'status'];


    public function testSeries()
    {
        return $this->belongsTo(TestSeries::class, 'test_series_id');
    }
}
