# Changelog

All notable changes to `laravel-security-txt` will be documented in this file.

## 1.0.0 - 2025-01-26

- Initial release
- Fetch security.txt template from configurable remote URL
- Replace placeholders with dynamic values (strings or callables)
- Auto-calculate Expires field in ISO 8601 format
- Serve file at `/.well-known/security.txt`
- RFC 9116 validation (Contact and Expires fields required)
- Graceful error handling with logging
- Configurable expiration days
- `security-txt:update` Artisan command with `--expires-days` option
