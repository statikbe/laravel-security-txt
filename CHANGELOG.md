# Changelog

All notable changes to `laravel-security-txt` will be documented in this file.

## v1.0.0 - Initial Release - 2026-01-26

A Laravel package to manage [security.txt](https://securitytxt.org/) files with automatic updates and configurable expiration.

### Features

- **Remote Template Fetching** - Fetch security.txt templates from any URL (e.g., GitHub raw files)
- **Placeholder Replacement** - Replace `{{PLACEHOLDER}}` syntax with configured values (strings or callables)
- **Auto-calculated Expiration** - Built-in `{{EXPIRES}}` placeholder automatically calculates ISO 8601 expiration dates
- **RFC 9116 Validation** - Validates generated files require `Contact` and `Expires` fields
- **Conditional Route** - Serve at `/.well-known/security.txt` with configurable middleware
- **Comment Stripping** - Comments (lines starting with `#`) are removed when serving the file
- **Graceful Error Handling** - Failed template fetches are logged without breaking existing files

### Artisan Command

```bash
php artisan security-txt:update
php artisan security-txt:update --expires-days=30
```

### Configuration

```php
'enabled' => env('SECURITY_TXT_ENABLED', true),
'template_url' => env('SECURITY_TXT_TEMPLATE_URL'),
'expires_days' => env('SECURITY_TXT_EXPIRES_DAYS', 365),
'output_path' => storage_path('security.txt'),
'placeholders' => [],
'middleware' => ['web'],
```

### Requirements

- PHP 8.2+
- Laravel 11.x or 12.x

### Installation

```bash
composer require statikbe/laravel-security-txt
php artisan vendor:publish --tag="security-txt-config"
```
