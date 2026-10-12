<?php

use App\Models\Footer;
use App\Support\WebsiteSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('footers')) {
            return;
        }

        foreach (WebsiteSettings::defaults() as $key => $meta) {
            $existing = Footer::where('key_name', $key)->first();
            if ($existing) {
                if (trim((string) $existing->value) === '') {
                    $existing->update([
                        'name' => $meta['name'],
                        'value' => $meta['value'],
                        'status' => 1,
                    ]);
                }
                continue;
            }

            Footer::create([
                'name' => $meta['name'],
                'key_name' => $key,
                'value' => $meta['value'],
                'status' => 1,
            ]);
        }

        $phone = WebsiteSettings::get('phone', '8100392229');
        $contact = Footer::where('key_name', 'contact_us')->first();
        if (!$contact) {
            Footer::create([
                'name' => 'Contact Us',
                'key_name' => 'contact_us',
                'value' => $phone,
                'status' => 1,
            ]);
        } elseif (trim((string) $contact->value) === '') {
            $contact->update(['value' => $phone, 'status' => 1]);
        }

        WebsiteSettings::flush();
    }

    public function down(): void
    {
        // Keep settings rows; they are used by the live site.
    }
};
