<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePackageFeature extends Model
{
    protected $fillable = [
        'course_package_id',
        'text',
        'sort_order',
    ];

    public function package()
    {
        return $this->belongsTo(CoursePackage::class, 'course_package_id');
    }
}
