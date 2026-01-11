<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class LocaleController extends Controller
{
    public function translations($locale)
    {
        App::setLocale($locale);

        $path = resource_path("lang/{$locale}.json");

        if (!File::exists($path)) {
            abort(404);
        }

        return response()->json(json_decode(File::get($path), true));
    }
}
