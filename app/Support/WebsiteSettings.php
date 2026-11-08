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
            'site_name' => [
                'name' => 'Website name',
                'value' => 'RankPro',
                'group' => 'general',
                'type' => 'text',
                'hint' => 'Used in the footer copyright line and public site labels.',
            ],
            'copyright' => [
                'name' => 'Copyright text',
                'value' => 'RankPro. All Rights Reserved.',
                'group' => 'general',
                'type' => 'text',
                'hint' => 'Shown at the bottom of every public page.',
            ],
            'email' => [
                'name' => 'Support email',
                'value' => 'support.rankproteam@gmail.com',
                'group' => 'contact',
                'type' => 'email',
                'placeholder' => 'support.rankproteam@gmail.com',
                'hint' => 'Footer, contact page, help page and mailto links.',
            ],
            'phone' => [
                'name' => 'Phone number',
                'value' => '8100392229',
                'group' => 'contact',
                'type' => 'text',
                'placeholder' => '8100392229',
                'hint' => 'Call Us in the footer and the floating phone button.',
            ],
            'whatsapp' => [
                'name' => 'WhatsApp number',
                'value' => '8100392229',
                'group' => 'contact',
                'type' => 'text',
                'placeholder' => '8100392229',
                'hint' => 'WhatsApp links, scholarship form and the floating WhatsApp button.',
            ],
            'toll_free' => [
                'name' => 'Toll free number',
                'value' => '',
                'group' => 'contact',
                'type' => 'text',
                'placeholder' => '1800 88 99 430',
                'hint' => 'Optional. Shown in the footer when filled.',
            ],
            'address' => [
                'name' => 'Location / address',
                'value' => '',
                'group' => 'contact',
                'type' => 'textarea',
                'placeholder' => 'Office address',
                'hint' => 'Footer and contact page.',
            ],
            'facebook' => [
                'name' => 'Facebook URL',
                'value' => 'https://www.facebook.com/share/1EUzavki4l/?mibextid=wwXIfr',
                'group' => 'social',
                'type' => 'text',
                'placeholder' => 'https://www.facebook.com/...',
            ],
            'instagram' => [
                'name' => 'Instagram URL',
                'value' => 'https://www.instagram.com/rankpro1',
                'group' => 'social',
                'type' => 'text',
                'placeholder' => 'https://www.instagram.com/...',
            ],
            'linkedin' => [
                'name' => 'LinkedIn URL',
                'value' => 'https://www.linkedin.com/company/rank-pro-25/',
                'group' => 'social',
                'type' => 'text',
                'placeholder' => 'https://www.linkedin.com/...',
            ],
            'youtube' => [
                'name' => 'YouTube URL',
                'value' => 'https://www.youtube.com/channel/UCc6roC0m21vsjIC4Ef9DYrg',
                'group' => 'social',
                'type' => 'text',
                'placeholder' => 'https://www.youtube.com/...',
            ],
            'twitter' => [
                'name' => 'X / Twitter URL',
                'value' => '',
                'group' => 'social',
                'type' => 'text',
                'placeholder' => 'https://x.com/...',
            ],
        ];
    }

    public static function groups(): array
    {
        return [
            'general' => [
                'title' => 'General',
                'intro' => 'Basic public identity used across the site.',
            ],
            'contact' => [
                'title' => 'Contact details',
                'intro' => 'These numbers and the email appear in the footer, contact page, help page and floating Call / WhatsApp buttons.',
            ],
            'social' => [
                'title' => 'Social media',
                'intro' => 'Leave a field blank to hide that network. Add https:// if the link does not include it.',
            ],
        ];
    }

    public static function fieldsIn(string $group): array
    {
        return array_filter(self::defaults(), function ($meta) use ($group) {
            return ($meta['group'] ?? 'general') === $group;
        });
    }

    public static function all(): array
    {
        $values = [];
        foreach (self::defaults() as $key => $meta) {
            $values[$key] = $meta['value'];
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

        foreach ($stored as $key => $value) {
            $values[$key] = $value === null ? '' : (string) $value;
        }

        return $values;
    }

    public static function get(string $key, $default = ''): string
    {
        $all = self::all();

        if (array_key_exists($key, $all)) {
            return (string) $all[$key];
        }

        if ($key === 'phone' && !empty($all['contact_us'])) {
            return (string) $all['contact_us'];
        }

        if ($key === 'whatsapp' && !empty($all['contact_us'])) {
            return (string) $all['contact_us'];
        }

        if ($default !== '') {
            return (string) $default;
        }

        return (string) (self::defaults()[$key]['value'] ?? '');
    }

    public static function digits(string $key = 'phone'): string
    {
        $source = preg_split('/[\/,;|]/', self::get($key))[0] ?? '';
        $digits = preg_replace('/\D+/', '', (string) $source) ?? '';

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
        $raw = trim(self::get($key));
        if ($raw === '') {
            return '';
        }

        $digits = preg_replace('/\D+/', '', preg_split('/[\/,;|]/', $raw)[0] ?? '') ?? '';
        if (strlen($digits) === 10) {
            return '+91 ' . $digits;
        }
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            return '+91 ' . substr($digits, 2);
        }

        return $raw;
    }

    public static function socialLinks(): array
    {
        $networks = [
            'facebook' => ['label' => 'Facebook', 'icon' => 'fab fa-facebook-f'],
            'instagram' => ['label' => 'Instagram', 'icon' => 'fab fa-instagram'],
            'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in'],
            'youtube' => ['label' => 'YouTube', 'icon' => 'fab fa-youtube'],
            'twitter' => ['label' => 'X', 'icon' => 'fab fa-twitter'],
        ];

        $links = [];
        foreach ($networks as $key => $meta) {
            $href = self::normalizeUrl(self::get($key));
            if ($href === '') {
                continue;
            }
            $links[] = $meta + ['key' => $key, 'href' => $href];
        }

        return $links;
    }

    public static function normalizeUrl(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        if (!preg_match('#^https?://#i', $value)) {
            return 'https://' . ltrim($value, '/');
        }

        return $value;
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
