<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Question_detail extends Model
{
    protected static function booted()
    {
        static::saving(function (Question_detail $detail) {
            $detail->content_hash = static::contentHash(
                $detail->question_text,
                $detail->option1,
                $detail->option2,
                $detail->option3,
                $detail->option4,
                $detail->is_option1_image,
                $detail->is_option2_image,
                $detail->is_option3_image,
                $detail->is_option4_image
            );
        });

        static::saved(function (Question_detail $detail) {
            static::forgetDuplicateCache();
        });
    }

    public static function duplicateCacheKey($languageId)
    {
        $version = Cache::get('question-duplicate-version', 1);

        return 'question-duplicate-hashes-'.$version.'-'.$languageId;
    }

    public static function forgetDuplicateCache()
    {
        $version = (int) Cache::get('question-duplicate-version', 1);
        Cache::forever('question-duplicate-version', $version + 1);
    }

    public static function normalizeContent($value)
    {
        $value = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = strip_tags($value);
        $value = str_replace("\xc2\xa0", ' ', $value);
        $value = preg_replace('/\s+/u', ' ', $value);

        return trim(mb_strtolower($value ?? ''));
    }

    public static function contentHash($questionText, $option1, $option2, $option3, $option4, $isOption1Image = 0, $isOption2Image = 0, $isOption3Image = 0, $isOption4Image = 0)
    {
        $parts = [
            static::normalizeContent($questionText),
            $isOption1Image ? 'img:'.trim((string) $option1) : static::normalizeContent($option1),
            $isOption2Image ? 'img:'.trim((string) $option2) : static::normalizeContent($option2),
            $isOption3Image ? 'img:'.trim((string) $option3) : static::normalizeContent($option3),
            $isOption4Image ? 'img:'.trim((string) $option4) : static::normalizeContent($option4),
        ];

        foreach ($parts as $part) {
            $check = strncmp($part, 'img:', 4) === 0 ? substr($part, 4) : $part;
            if ($check !== '') {
                return md5(implode("\n", $parts));
            }
        }

        return null;
    }

    protected $fillable = [
        'question_id',
        'language_id',
        'solution',
        'question_source_id',
        'question_text',
        'question_image',
        'option1',
        'is_option1_image',
        'option2',
        'is_option2_image',
        'option3',
        'is_option3_image',
        'option4',
        'is_option4_image',
        'answer_behavior_tag1',
        'answer_behavior_tag2',
        'answer_behavior_tag3',
        'answer_behavior_tag4',
        'status',
        'created_at',
        'updated_at',
    ];
}
