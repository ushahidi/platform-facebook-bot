<?php
    return [
        'ushahidi' => [
            'platform_username' => env('PLATFORM_USERNAME'),
            'platform_password' => env('PLATFORM_PASSWORD'),
            'platform_client_id' => env('PLATFORM_CLIENT_ID'),
            'platform_token_url' => env('PLATFORM_TOKEN_URL'),
            'platform_client_secret' => env('PLATFORM_CLIENT_SECRET'),
            'platform_base_uri' => env('PLATFORM_BASE_URI')
        ],
        'facebook' => [
            'facebook_access_token' => env('FACEBOOK_ACCESS_TOKEN'),
            'facebook_api_url' => env('FACEBOOK_API_URL'),
            'facebook_verify_token' => env('FACEBOOK_VERIFY_TOKEN')
        ]
    ];
    