<?php

namespace Statik\LaravelSecurityTxt\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Statik\LaravelSecurityTxt\LaravelSecurityTxt
 */
class LaravelSecurityTxt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Statik\LaravelSecurityTxt\LaravelSecurityTxt::class;
    }
}
