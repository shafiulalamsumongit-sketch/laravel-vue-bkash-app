<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocaleController;

Route::get('/', function () {
    return view('welcome');
});


/* Route::post('/language', function (\Illuminate\Http\Request $request) {
    session(['locale' => $request->lang]);
    return response()->json(['locale' => $request->lang , 'success' => true]);
}); */

/* Route::get('/translations', function () {
    return trans()->get('*');
});
 */
Route::get('/translations/{locale}', [LocaleController::class, 'translations']);

Route::post('/set-locale', function (\Illuminate\Http\Request $request) {
    session(['locale' => $request->locale]);
    app()->setLocale($request->locale);
});