<?php

namespace App\Support;

use App\Models\Footer;
use Illuminate\Support\Facades\Cache;
use Throwable;

class WebsiteSettings
{
    public const CACHE_KEY = 'rankpro.website_settings';

    public static function defaults(): array
    {
        return [
            'phone' => [
                'name' => 'Call Us',
                'value' => '8100392229',
            ],
            'whatsapp' => [
                'name' => 'WhatsApp',
                'value' => '8100392229',
            ],
            'email' => [
                'name' => 'Email',
                'value' => 'support.rankproteam@gmail.com',
            ],
            'address' => [
                'name' => 'Address',
                'value' => '',
            ],
            'facebook' => [
                'name' => 'Facebook',
                'value' => 'https://www.facebook.com/share/1EUzavki4l/?mibextid=wwXIfr',
            ],
            'instagram' => [
                'name' => 'Instagram',
                'value' => 'https://www.instagram.com/rankpro1',
            ],
            'linkedin' => [
                'name' => 'LinkedIn',
                'value' => 'https://www.linkedin.com/company/rank-pro-25/',
            ],
            'youtube' => [
                'name' => 'YouTube',
                'value' => '',
            ],
            'copyright' => [
                'name' => 'Copyright',
                'value' => 'RankPro. All Rights Reserved.',
            ],
        ];
    }

    public static function all(): array
    {
        $defaults = [];
        foreach (self::defaults() as $key => $meta) {
            $defaults[$key] = $meta['value'];
        }

        try {
            $stored = Cache::remember(self::CACHE_KEY, 600, function () {
                return Footer::query()
                    ->where('status', 1)
                    ->pluck('value', 'key_name')
                    ->toArray();
            });
        } catch (Throwable $e) {
            $stored = [];
        }

        return array_merge($defaults, array_filter($stored, function ($value) {
            return $value !== null && $value !== '';
        }));
    }

    public static function get(string $key, $default = ''): string
    {
        $all = self::all();

        if (isset($all[$key]) && $all[$key] !== '') {
            return (string) $all[$key];
        }

        if ($key === 'phone' && !empty($all['contact_us'])) {
            return (string) $all['contact_us'];
        }

        if ($key === 'whatsapp' && !empty($all['contact_us'])) {
            return (string) $all['contact_us'];
        }

        return (string) ($default !== '' ? $default : (self::defaults()[$key]['value'] ?? ''));
    }

    public static function digits(string $key = 'phone'): string
    {
        $source = preg_split('/[\/,;|]/', self::get($key))[0] ?? '';
        $digits = preg_replace('/\D+/', '', (string) $source);

        if (strlen($digits) === 10) {
            $digits = '91' . $digits;
        }

        if ($digits === '') {
            $digits = '918100392229';
        }

        return $digits;
    }

    public static function telHref(string $key = 'phone'): string
    {
        return 'tel:+' . self::digits($key);
    }

    public static function whatsappHref(): string
    {
        return 'https://api.whatsapp.com/send?phone=' . self::digits('whatsapp');
    }

    public static function displayPhone(string $key = 'phone'): string
    {
        $digits = self::digits($key);
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return '+91 ' . substr($digits, 2);
        }

        return self::get($key);
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
