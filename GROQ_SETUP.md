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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),
    ],

    'whatsapp' => [
        'provider' => env('WHATSAPP_PROVIDER', 'fonnte'), // 'fonnte' or 'wablas'
        'api_url' => env('WHATSAPP_API_URL', 'https://api.fonnte.com'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
    ],

    'ai_agent' => [
        'provider' => env('AI_AGENT_PROVIDER', 'groq'), // 'groq', 'gemini', 'openrouter', 'ollama', 'openai'
        'api_key' => env('AI_AGENT_API_KEY'),
        'api_url' => env('AI_AGENT_API_URL'),
        'model' => env('AI_AGENT_MODEL'),
        'temperature' => env('AI_AGENT_TEMPERATURE', 0.7),
        'max_tokens' => env('AI_AGENT_MAX_TOKENS', 1000),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'temperature' => env('GROQ_TEMPERATURE', 0.7),
        'max_tokens' => env('GROQ_MAX_TOKENS', 1000),
        'session_driver' => env('SESSION_DRIVER', 'file'),
    ],
];
