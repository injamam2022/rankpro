<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSeries extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'name', 'subjects', 'price', 'dis_price', 'tax', 'status'];


    public function descriptions()
    {
        return $this->hasMany(TestSeriesDescription::class, 'test_series_id');
    }

    public function headings()
    {
        return $this->hasMany(TestSeriesHeading::class);
    }

    public function abouts()
    {
        return $this->hasMany(TestSeriesAbout::class);
    }

    public function overviews()
    {
        return $this->hasMany(TestSeriesOverview::class);
    }

    public function examdescs()
    {
        return $this->hasMany(TestSeriesExamdesc::class);
    }

    public function markschemes()
    {
        return $this->hasMany(TestSeriesMarkscheme::class);
    }

    public function language()
    {
        return $this->hasMany(TestSeriesLanguage::class);
    }

    public function location()
    {
        return $this->hasMany(TestSeriesLocation::class);
    }

    public function date()
    {
        return $this->hasMany(TestSeriesDate::class);
    }
}
