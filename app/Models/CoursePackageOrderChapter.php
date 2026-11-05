<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePackageOrderChapter extends Model
{
    protected $fillable = [
        'course_package_order_id',
        'user_id',
        'chapter_id',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(CoursePackageOrder::class, 'course_package_order_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
