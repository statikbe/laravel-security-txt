<?php

namespace Statik\LaravelSecurityTxt\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Statik\LaravelSecurityTxt\LaravelSecurityTxtServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelSecurityTxtServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('security-txt.output_path', $this->getTempPath());
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }

    protected function getTempPath(): string
    {
        return sys_get_temp_dir().'/security-txt-test/security.txt';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $dir = dirname($this->getTempPath());
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        $path = $this->getTempPath();
        $dir = dirname($path);

        if (file_exists($path)) {
            unlink($path);
        }

        if (is_dir($dir)) {
            @rmdir($dir);
        }

        parent::tearDown();
    }
}
