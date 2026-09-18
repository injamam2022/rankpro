<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserRoleAccess extends Model
{
    protected $primary_key = 'id';

    protected $fillable = [
        'admin_user_role_id','admin_user_access_id','parent_id','is_add','is_edit','is_list','is_delete','is_reorder','is_copy','is_export','is_other','is_active','created_at','updated_at'
    ];

    public function menu()
    {
        return $this->hasOne('App\Models\AdminUserAccess', 'id', 'admin_user_access_id');
    }

}
