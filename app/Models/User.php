<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rankpro_id',
        'first_name',
        'last_name',
        'address',
        'mobile_number',
        'is_whatsapp',
        'email_id',
        'father_full_name',
        'father_occupation',
        'father_mobile_number',
        'father_qualification',
        'mother_full_name',
        'mother_occupation',
        'mother_mobile_number',
        'mother_qualification',
        'password',
        'profile_img',
        'qualification_details',
        'school_name',
        'class_name',
        'section_name',
        'certificate',
        'facebook_link',
        'instagram_link',
        'youtube_link',
        'twitter_link',
        'whats_app_link',
        'linkedin_link',
        'guardian_signature',
        'student_signature',
        'counsellor_id',
        'user_tag_id',
        'parant_password',
        'coupon_code',
        'package',
        'xp_points',
        'mark',
        'total_mark',
        'rank',
        'mark_avg',
        'is_lead',
        'otp',
        'verification_code',
        'verification_code_expiry',
        'access_token',
        'status',
        'created_at',
        'updated_at',
        'is_deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'parant_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
