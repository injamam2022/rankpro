<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'keywords',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'integer',
    ];

    public function scopePublished($query)
    {
        // "Active" in admin = visible on the public blog.
        return $query->where('status', 1);
    }

    public function isLive(): bool
    {
        return (int) $this->status === 1;
    }

    public function getImageUrlAttribute()
    {
        return $this->resolveImageUrl(preferThumb: false);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->resolveImageUrl(preferThumb: true);
    }

    private function resolveImageUrl(bool $preferThumb = false): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $fullRel = 'uploads/blog/' . ltrim($this->image, '/');
        $thumbRel = 'uploads/blog/thumbnail/' . ltrim($this->image, '/');
        $fullAbs = public_path($fullRel);
        $thumbAbs = public_path($thumbRel);

        $rel = null;
        if ($preferThumb && is_file($thumbAbs)) {
            $rel = $thumbRel;
        } elseif (is_file($fullAbs)) {
            $rel = $fullRel;
        } elseif (is_file($thumbAbs)) {
            $rel = $thumbRel;
        } else {
            // File may exist on another machine / CDN; still expose the expected public path.
            $rel = $fullRel;
        }

        $url = asset($rel);
        if ($this->updated_at) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'v=' . $this->updated_at->timestamp;
        }

        return $url;
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
