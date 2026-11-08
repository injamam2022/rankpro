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

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->rankpro_id)) {
                $user->rankpro_id = static::generateRankproId();
            }
        });
    }

    /**
     * NEET cycle used in RankPro IDs. 27 = 2027, so IDs look like RP/27/0001.
     */
    public const RANKPRO_ID_YEAR = '27';

    /**
     * Sequential RankPro ID: RP/{year}/{4-digit sequence}, e.g. RP/27/0001.
     */
    public static function generateRankproId(): string
    {
        $prefix = 'RP/'.self::RANKPRO_ID_YEAR.'/';

        $lastId = static::query()
            ->where('rankpro_id', 'like', $prefix.'%')
            ->orderByRaw("CAST(SUBSTRING_INDEX(rankpro_id, '/', -1) AS UNSIGNED) DESC")
            ->value('rankpro_id');

        $maxSeq = 0;
        if (is_string($lastId) && preg_match('#/(\d+)$#', $lastId, $matches)) {
            $maxSeq = (int) $matches[1];
        }

        do {
            $maxSeq++;
            $rankproId = $prefix.str_pad((string) $maxSeq, 4, '0', STR_PAD_LEFT);
        } while (static::where('rankpro_id', $rankproId)->exists());

        return $rankproId;
    }
}
