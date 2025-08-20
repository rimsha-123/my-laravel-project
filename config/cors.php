<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000', // Local React
        // 'https://your-frontend-domain.com', // Live React domain
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Authorization'],

    // Agar tum "Bearer token" use kar rahi ho, to false rakho
    'supports_credentials' => false,
];
