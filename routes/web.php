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

Route::get('/', [PageController::class, 'home']);

Route::get('/login', [AuthController::class, 'login'])->name('auth.view');
Route::post('/login', [AuthController::class, 'loginPost'])->name('auth.post');

Route::get('/register', [AuthController::class, 'register'])->name('auth.register.view');
Route::post('/register', [AuthController::class, 'registerPost'])->name('auth.register.post');
