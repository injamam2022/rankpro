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
                $existing->update([
                    'name' => $meta['name'],
                    'status' => 1,
                ]);
                continue;
            }

            Footer::create([
                'name' => $meta['name'],
                'key_name' => $key,
                'value' => $meta['value'],
                'status' => 1,
            ]);
        }

        $contact = [
            'email' => 'support.rankproteam@gmail.com',
            'phone' => '8100392229',
            'whatsapp' => '8100392229',
        ];

        foreach ($contact as $key => $value) {
            Footer::updateOrCreate(
                ['key_name' => $key],
                [
                    'name' => WebsiteSettings::defaults()[$key]['name'],
                    'value' => $value,
                    'status' => 1,
                ]
            );
        }

        Footer::updateOrCreate(
            ['key_name' => 'contact_us'],
            [
                'name' => 'Contact Us',
                'value' => $contact['phone'],
                'status' => 1,
            ]
        );

        WebsiteSettings::flush();
    }

    public function down(): void
    {
        // Keep live contact details.
    }
};
