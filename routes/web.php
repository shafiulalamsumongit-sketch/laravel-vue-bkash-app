<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;

Route::get('/translations/{locale}', [LocaleController::class, 'translations']);

Route::post('/set-locale', function (\Illuminate\Http\Request $request) {
    session(['locale' => $request->locale]);
    app()->setLocale($request->locale);
});

Route::get('/{any}', function () {
    return view('app'); // Vue entry blade
})->where('any', '.*');




