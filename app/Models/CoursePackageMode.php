<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePackageMode extends Model
{
    protected $fillable = [
        'course_package_id',
        'mode_code',
        'label',
        'price',
        'requires_location',
        'requires_address',
        'includes_online',
        'includes_omr',
        'help_text',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price' => 'float',
        'requires_location' => 'boolean',
        'requires_address' => 'boolean',
        'includes_online' => 'boolean',
        'includes_omr' => 'boolean',
    ];

    public function package()
    {
        return $this->belongsTo(CoursePackage::class, 'course_package_id');
    }
}
