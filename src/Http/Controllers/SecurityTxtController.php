<?php

namespace Statik\LaravelSecurityTxt\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class SecurityTxtController
{
    public function __invoke(): Response
    {
        $path = config('security-txt.output_path');

        abort_unless(File::exists($path), 404);

        $content = preg_replace('/^\s*#.*$\n?/m', '', File::get($path));

        return response(trim($content), 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
