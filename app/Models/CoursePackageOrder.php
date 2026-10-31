<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoursePackageOrder extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'course_package_id',
        'course_package_mode_id',
        'mode_code',
        'mode_label',
        'amount',
        'discount',
        'coupon_discount',
        'coupon_code',
        'tax',
        'final_amount',
        'location_id',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_pincode',
        'fulfillment_status',
        'payment_status',
        'transaction_id',
        'gateway_order_id',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'discount' => 'float',
        'coupon_discount' => 'float',
        'tax' => 'float',
        'final_amount' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function (CoursePackageOrder $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'CP-' . strtoupper(Str::random(4)) . '-' . now()->format('ymdHis');
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(CoursePackage::class, 'course_package_id');
    }

    public function mode()
    {
        return $this->belongsTo(CoursePackageMode::class, 'course_package_mode_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function isPaid(): bool
    {
        return in_array($this->payment_status, ['SUCCESS', 'FREE'], true);
    }

    public function modeBadge(): string
    {
        return match ($this->mode_code) {
            'online' => 'Online',
            'centre' => 'Centre / Offline',
            'delivery' => 'Home Delivery',
            'bundle' => 'Online + OMR',
            default => $this->mode_label ?: '—',
        };
    }
}
