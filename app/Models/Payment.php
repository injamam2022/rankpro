<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_id',
        'type',
        'test_series_id',
        'ranker_id',
        'language',
        'location',
        'starting_date',
        'coupon_discount',
        'coupon_code',
        'amount',
        'discount',
        'name',
        'email',
        'mobile_number',
        'payment_amount',
        'payment_status',
        'status',
        'created_at',
        'updated_at',
    ];
}
