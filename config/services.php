<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'twofactor' => [
        'api_key' => env('TWOFACTOR_API_KEY', '604aef81-03bd-11f0-8b17-0200cd936042'),
        'otp_template' => env('TWOFACTOR_OTP_TEMPLATE', 'OTPtemplate'),
    ],

    /*
    | Razorpay — use config('services.razorpay.*') in views/controllers.
    | env() returns null on servers after `php artisan config:cache`.
    */
    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
        'name' => env('RAZORPAY_NAME', 'RankPro'),
        'description' => env('RAZORPAY_DESCRIPTION', 'RankPro'),
    ],

    /*
    | ICICI Bank Orange PG (UAT defaults). Switch the URLs for production.
    */
    'icici' => [
        'merchant_id' => env('ICICI_MERCHANT_ID'),
        'aggregator_id' => env('ICICI_AGGREGATOR_ID'),
        'secret' => env('ICICI_SECRET_KEY'),
        'sale_url' => env('ICICI_SALE_URL', 'https://pgpayuat.icicibank.com/tsp/pg/api/v2/initiateSale'),
        'command_url' => env('ICICI_COMMAND_URL', 'https://pgpayuat.icici.bank.in/tsp/pg/api/command'),
    ],

];
