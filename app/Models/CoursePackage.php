<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoursePackage extends Model
{
    protected $fillable = [
        'code',
        'name',
        'slug',
        'tagline',
        'description',
        'image',
        'theme',
        'display_price',
        'tax',
        'is_free',
        'includes_online',
        'includes_omr',
        'mode_selection',
        'schedule_scope',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'display_price' => 'float',
        'tax' => 'float',
        'is_free' => 'boolean',
        'includes_online' => 'boolean',
        'includes_omr' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (CoursePackage $package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });
    }

    public function features()
    {
        return $this->hasMany(CoursePackageFeature::class)->orderBy('sort_order');
    }

    public function modes()
    {
        return $this->hasMany(CoursePackageMode::class)->orderBy('sort_order');
    }

    public function activeModes()
    {
        return $this->modes()->where('status', 1);
    }

    public function orders()
    {
        return $this->hasMany(CoursePackageOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function lowestModePrice(): ?float
    {
        $mode = $this->activeModes()->orderBy('price')->first();
        return $mode ? (float) $mode->price : (float) $this->display_price;
    }

    public function formattedPrice(): string
    {
        if ($this->is_free) {
            return 'Free';
        }

        $price = $this->lowestModePrice();
        if ($this->mode_selection === 'choose_one' && $this->activeModes()->count() > 1) {
            return '₹' . number_format($price, 0) . '+';
        }

        return '₹' . number_format($price, 0);
    }
}
