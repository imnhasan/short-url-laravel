<?php

use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::controller(ShortUrlController::class)->group(function () {
    Route::get('/', 'index')->name('short_url.index');
    Route::get('/{shortUrl}', 'index');
    Route::post('/', 'store')->name('short_url.store');
    Route::put('/update', 'update')->name('short_url.update');
    Route::delete('/destroy/{shortUrl}', 'destroy')->name('short_url.destroy');
});
