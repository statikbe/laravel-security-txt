<?php

namespace Statik\LaravelSecurityTxt\Commands;

use Illuminate\Console\Command;

class LaravelSecurityTxtCommand extends Command
{
    public $signature = 'laravel-security-txt';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
