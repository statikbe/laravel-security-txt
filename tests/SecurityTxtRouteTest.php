<?php

use Illuminate\Support\Facades\Route;

it('returns security.txt content when file exists', function () {
    $content = "Contact: mailto:security@example.com\nExpires: 2025-12-31T23:59:59+00:00";
    file_put_contents(config('security-txt.output_path'), $content);

    $this->get('/.well-known/security.txt')
        ->assertStatus(200)
        ->assertHeader('Content-Type', 'text/plain; charset=utf-8')
        ->assertSee('Contact: mailto:security@example.com')
        ->assertSee('Expires: 2025-12-31T23:59:59+00:00');
});

it('returns 404 when file does not exist', function () {
    @unlink(config('security-txt.output_path'));

    $this->get('/.well-known/security.txt')
        ->assertStatus(404);
});

it('registers route when enabled', function () {
    expect(Route::has('security-txt'))->toBeTrue();
});

it('strips comments from output', function () {
    $content = "# This is a comment\nContact: mailto:security@example.com\n# Another comment\nExpires: 2025-12-31T23:59:59+00:00";
    file_put_contents(config('security-txt.output_path'), $content);

    $this->get('/.well-known/security.txt')
        ->assertStatus(200)
        ->assertDontSee('# This is a comment')
        ->assertDontSee('# Another comment')
        ->assertSee('Contact: mailto:security@example.com')
        ->assertSee('Expires: 2025-12-31T23:59:59+00:00');
});
