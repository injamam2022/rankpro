<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSeriesLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['test_series_id', 'language_name', 'status'];
}
