<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Source extends Model
{
    protected $fillable = [
        'subject_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Sources named "Chapter Question" / "Chapter Questions".
     * Only questions tagged with these belong in Chapter Master.
     *
     * @return array<int>
     */
    public static function chapterMasterIds(): array
    {
        if (! Schema::hasTable('sources')) {
            return [];
        }

        return static::query()
            ->where('status', 1)
            ->where('name', 'like', 'Chapter Question%')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
