<?php

use Illuminate\Support\Facades\Route;
use Statik\LaravelSecurityTxt\Http\Controllers\SecurityTxtController;

Route::get('.well-known/security.txt', SecurityTxtController::class)
    ->middleware(config('security-txt.middleware', ['web']))
    ->name('security-txt');
