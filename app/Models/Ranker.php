<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranker extends Model
{
    protected $fillable = [
        'name','email','password','phone_number','landing_icon',
        'profile_icon','icon','college_name','about','description',
        'mentorship','test_series','location','access_token',
        'score','college','year','air','video_link',
        'subject_id','language_id','course_id','is_in_listing',
        'hash_code','status','created_at','updated_at'
    ];

    public function feedbacks()
    {
        return $this->hasMany(RankerFeedbacks::class, 'ranker_id');
    }

    public function meetings()
    {
        return $this->hasMany(Ranker_price::class, 'ranker_id');
    }
}
