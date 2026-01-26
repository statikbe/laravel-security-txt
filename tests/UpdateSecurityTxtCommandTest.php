<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('security-txt.template_url', 'https://example.com/security.txt.template');
});

it('fails when template_url is not configured', function () {
    config()->set('security-txt.template_url', null);

    $this->artisan('security-txt:update')
        ->expectsOutput('No template URL configured. Set SECURITY_TXT_TEMPLATE_URL or configure template_url in config/security-txt.php')
        ->assertExitCode(1);
});

it('fetches template and creates security.txt file', function () {
    Http::fake([
        'https://example.com/security.txt.template' => Http::response(
            "Contact: mailto:security@example.com\nExpires: {{EXPIRES}}"
        ),
    ]);

    $this->artisan('security-txt:update')
        ->assertExitCode(0);

    $path = config('security-txt.output_path');
    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents($path);
    expect($content)->toContain('Contact: mailto:security@example.com');
    expect($content)->toContain('Expires: ');
    expect($content)->not->toContain('{{EXPIRES}}');
});

it('replaces custom placeholders', function () {
    config()->set('security-txt.placeholders', [
        'CONTACT_EMAIL' => 'test@example.com',
        'PGP_KEY_URL' => fn () => 'https://example.com/pgp.txt',
    ]);

    Http::fake([
        'https://example.com/security.txt.template' => Http::response(
            "Contact: mailto:{{CONTACT_EMAIL}}\nExpires: {{EXPIRES}}\nEncryption: {{PGP_KEY_URL}}"
        ),
    ]);

    $this->artisan('security-txt:update')
        ->assertExitCode(0);

    $content = file_get_contents(config('security-txt.output_path'));
    expect($content)->toContain('Contact: mailto:test@example.com');
    expect($content)->toContain('Encryption: https://example.com/pgp.txt');
});

it('uses --expires-days option to override config', function () {
    Http::fake([
        'https://example.com/security.txt.template' => Http::response(
            "Contact: mailto:security@example.com\nExpires: {{EXPIRES}}"
        ),
    ]);

    $this->artisan('security-txt:update', ['--expires-days' => 30])
        ->assertExitCode(0);

    $content = file_get_contents(config('security-txt.output_path'));

    $expectedDate = now()->addDays(30)->format('Y-m-d');
    expect($content)->toContain($expectedDate);
});

it('fails when template is missing Contact field', function () {
    Http::fake([
        'https://example.com/security.txt.template' => Http::response(
            'Expires: {{EXPIRES}}'
        ),
    ]);

    $this->artisan('security-txt:update')
        ->expectsOutput('RFC 9116 validation failed: Missing required "Contact" field')
        ->assertExitCode(1);

    expect(file_exists(config('security-txt.output_path')))->toBeFalse();
});

it('fails when template is missing Expires field', function () {
    Http::fake([
        'https://example.com/security.txt.template' => Http::response(
            'Contact: mailto:security@example.com'
        ),
    ]);

    $this->artisan('security-txt:update')
        ->expectsOutput('RFC 9116 validation failed: Missing required "Expires" field')
        ->assertExitCode(1);
});

it('handles HTTP errors gracefully', function () {
    Http::fake([
        'https://example.com/security.txt.template' => Http::response('Not found', 404),
    ]);

    $this->artisan('security-txt:update')
        ->expectsOutputToContain('Failed to fetch template')
        ->assertExitCode(1);
});

it('handles connection errors gracefully', function () {
    Http::fake(function () {
        throw new \Exception('Connection refused');
    });

    $this->artisan('security-txt:update')
        ->expectsOutputToContain('Failed to fetch template')
        ->assertExitCode(1);
});
