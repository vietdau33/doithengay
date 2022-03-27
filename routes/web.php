<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/logs', [PageController::class, 'showLogs'])->name('logger');

Route::middleware('guest')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('view');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('post');

    Route::get('/register', [AuthController::class, 'register'])->name('register.view');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
});

Route::middleware('authenticated')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [PageController::class, 'profile'])->name('home');
        Route::get('change', [PageController::class, 'changeProfile'])->name('change');
        Route::post('change', [PageController::class, 'changeProfilePost'])->name('change.post');
        Route::get('password', [PageController::class, 'changePassword'])->name('password');
        Route::post('password', [PageController::class, 'changePasswordPost'])->name('password.post');
    });
});
