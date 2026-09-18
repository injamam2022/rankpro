<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'address','logo','location_name','location_description','country_id','state_id',
        'district_id','city_id','zip_code','phone_number','board_id','prefix_of_location',
        'status','created_at','updated_at'
    ];
}
