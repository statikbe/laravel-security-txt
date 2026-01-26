<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | When disabled, the /.well-known/security.txt route will not be registered.
    |
    */

    'enabled' => env('SECURITY_TXT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Template URL
    |--------------------------------------------------------------------------
    |
    | The remote URL to fetch the security.txt template from.
    | This could be a GitHub raw file URL or any other accessible URL.
    |
    | Example: 'https://raw.githubusercontent.com/your-org/security-txt/main/security.txt.template'
    |
    */

    'template_url' => env('SECURITY_TXT_TEMPLATE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Expiration Days
    |--------------------------------------------------------------------------
    |
    | Number of days until the security.txt expires. The {{EXPIRES}} placeholder
    | will be automatically calculated based on this value.
    |
    */

    'expires_days' => env('SECURITY_TXT_EXPIRES_DAYS', 365),

    /*
    |--------------------------------------------------------------------------
    | Output Path
    |--------------------------------------------------------------------------
    |
    | The path where the generated security.txt file will be stored.
    |
    */

    'output_path' => storage_path('security.txt'),

    /*
    |--------------------------------------------------------------------------
    | Placeholders
    |--------------------------------------------------------------------------
    |
    | Map placeholder names to values or callables. Placeholders in the template
    | use the format {{PLACEHOLDER_NAME}}. The {{EXPIRES}} placeholder is
    | automatically handled by the package.
    |
    | Example:
    |     'CONTACT_EMAIL' => 'security@example.com',
    |     'PGP_KEY_URL' => fn() => config('app.url') . '/pgp-key.txt',
    |
    */

    'placeholders' => [],

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware to apply to the security.txt route.
    |
    */

    'middleware' => ['web'],

];
