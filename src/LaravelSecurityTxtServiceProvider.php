<?php

namespace Statik\LaravelSecurityTxt;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Statik\LaravelSecurityTxt\Commands\UpdateSecurityTxtCommand;

class LaravelSecurityTxtServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('security-txt')
            ->hasConfigFile()
            ->hasCommand(UpdateSecurityTxtCommand::class);
    }

    public function packageRegistered(): void
    {
        if (config('security-txt.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
    }
}
