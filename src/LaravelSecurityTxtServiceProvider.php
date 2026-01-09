<?php

namespace Statik\LaravelSecurityTxt;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Statik\LaravelSecurityTxt\Commands\LaravelSecurityTxtCommand;

class LaravelSecurityTxtServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-security-txt')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_security_txt_table')
            ->hasCommand(LaravelSecurityTxtCommand::class);
    }
}
