<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\MoneyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CardController;

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
Route::get('/check-rate', [CardController::class, 'checkRate'])->name('check-rate');

Route::middleware('guest')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('view');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('post');

    Route::get('/register', [AuthController::class, 'register'])->name('register.view');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
});

Route::middleware('authenticated')->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/buy-card', [CardController::class, 'buyCard'])->name('buy-card');
    Route::post('/buy-card', [CardController::class, 'buyCardPost'])->name('buy-card.post');
    Route::get('/list-card-buy/{hash}', [CardController::class, 'listCardBuy'])->name('list-card');
    Route::get('/buy-card/history', [CardController::class, 'buyCardHistory'])->name('buy-card.history');

    Route::get('/trade-card', [CardController::class, 'tradeCard'])->name('trade-card');
    Route::post('/trade-card', [CardController::class, 'tradeCardPost'])->name('trade-card.post');
    Route::get('/trade-card/history', [CardController::class, 'tradeCardHistory'])->name('trade-card.history');

    Route::get('/check-trade-card', [CardController::class, 'checkTradeCard'])->name('check-trade-card');

    Route::get('/recharge', [MoneyController::class, 'recharge'])->name('recharge');

    Route::get('/withdraw', [MoneyController::class, 'withdraw'])->name('withdraw');
    Route::post('/withdraw', [MoneyController::class, 'withdrawPost'])->name('withdraw.post');
    Route::get('/withdraw/history', [MoneyController::class, 'withdrawHistory'])->name('withdraw.history');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [PageController::class, 'profile'])->name('home');
        Route::get('change', [PageController::class, 'changeProfile'])->name('change');
        Route::post('change', [PageController::class, 'changeProfilePost'])->name('change.post');
        Route::get('password', [PageController::class, 'changePassword'])->name('password');
        Route::post('password', [PageController::class, 'changePasswordPost'])->name('password.post');
    });

    Route::prefix('bank')->name('bank.')->group(function () {
        Route::get('/', [BankController::class, 'list'])->name('list');
        Route::get('/add', [BankController::class, 'add'])->name('add');
        Route::post('/add', [BankController::class, 'addPost'])->name('add.post');
        Route::get('/remove/{id}', [BankController::class, 'remove'])->name('remove');
    });
});

Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'admin.php';
});
