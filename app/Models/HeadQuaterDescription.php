<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadQuaterDescription extends Model
{
    use HasFactory;

    protected $fillable = ['head_quater_id', 'language', 'text', 'status'];


    public function headQuater()
    {
        return $this->belongsTo(HeadQuater::class, 'head_quater_id');
    }
}
