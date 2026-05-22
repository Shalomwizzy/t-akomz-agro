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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'paystack' => [
        'public_key'  => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key'  => env('PAYSTACK_SECRET_KEY'),
        'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    ],

    'flutterwave' => [
        'public_key'  => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key'  => env('FLUTTERWAVE_SECRET_KEY'),
        'secret_hash' => env('FLUTTERWAVE_SECRET_HASH'),
    ],

    'groq' => [
        'api_key'  => env('GROQ_API_KEY'),
        'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
        'model'    => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
    ],

    'gemini' => [
        'api_key'  => env('GEMINI_API_KEY'),
        'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
        'model'    => env('GEMINI_MODEL', 'gemini-2.0-flash'),
    ],

    'termii' => [
        'api_key'   => env('TERMII_API_KEY'),
        'sender_id' => env('TERMII_SENDER_ID', 'TAKOMZ'),
        'base_url'  => 'https://v3.api.termii.com/api',
    ],

    'pexels' => [
        'key' => env('PEXELS_API_KEY'),
    ],

    'onesignal' => [
        'app_id'   => env('ONESIGNAL_APP_ID'),
        'api_key'  => env('ONESIGNAL_REST_API_KEY'),
    ],

];
