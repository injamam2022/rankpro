<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentNotification extends Model
{
    protected $table = 'student_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'is_important',
        'notifiable_type',
        'notifiable_id',
        'action_url',
        'read_at',
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
