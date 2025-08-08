<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    */

    // Path yang perlu diatur CORS-nya
    'paths' => ['api/*', 'storage/*', '*'],

    // Method yang diizinkan
    'allowed_methods' => ['*'],

    // Origin yang diizinkan
    'allowed_origins' => env('APP_ENV') === 'production'
        ? ['https://mgbk.co.id']
        : ['http://localhost:5173'],

    'allowed_origins_patterns' => [],

    // Header yang diizinkan
    'allowed_headers' => ['*'],

    // Header yang diekspos
    'exposed_headers' => [],

    // Lama cache preflight
    'max_age' => 0,

    // Cookie atau credential
    'supports_credentials' => false,

];
