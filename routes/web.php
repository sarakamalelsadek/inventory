<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

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

Route::get('/test', function () {
    try {
        Cache::tags('test')->put('key', 'value', 10);
        dd('Tags supported');
    } catch (\InvalidArgumentException $e) {
        dd('Tags NOT supported');
    }
});


