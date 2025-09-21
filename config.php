<?php
// config.php - central configuration for Lupyana Tech
// Edit values below to match your environment
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'lupyanatech',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],

    'imgbb' => [
        // Get API key from https://imgbb.com/ and paste here
        'api_key' => '8a402848056a5c86e392b765263065e0',
        'upload_url' => 'https://api.imgbb.com/1/upload',
    ],

    'owner' => [
        // WhatsApp number in international format without + or punctuation, e.g. 255777123456
        'whatsapp' => '255778187338',
    ],

    'app' => [
        // Base URL for redirects and links. Edit to match your local XAMPP setup.
        'base_url' => 'http://localhost/lupyanatech',

        // Upload restrictions for payment screenshots
        'upload' => [
            'max_size' => 2 * 1024 * 1024, // 2 MB
            'allowed_types' => [
                'image/jpeg',
                'image/png',
                'image/webp',
            ],
        ],

        // CSRF secret (change to a random value in production)
        'csrf_salt' => 'change_this_random_salt',

        // Basic rate limiting (requests per minute per IP)
        'rate_limit' => [
            'requests_per_minute' => 60,
        ],
    ],
];
