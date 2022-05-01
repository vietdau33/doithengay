<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\BillController;
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

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/logs', [PageController::class, 'showLogs'])->name('logger');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/comming-soon', [PageController::class, 'commingSoon'])->name('comming-soon');
Route::get('/check-rate', [CardController::class, 'checkRate'])->name('check-rate');
Route::post('/administator/plus-money', [MoneyController::class, 'plusMoneyUser'])->name('plus-money');
Route::get('/auth/verify-link/{hash}', [AuthController::class, 'userVerifyHash']);

Route::middleware('guest')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('view');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('post');

    Route::get('/register', [AuthController::class, 'register'])->name('register.view');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

    Route::get('/forgot-password', [AuthController::class, 'forgot'])->name('forgot.view');
    Route::get('/forgot-password/{hash}', [AuthController::class, 'forgotVerify']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPost'])->name('forgot.post');
});

Route::middleware('need_verify')->group(function () {
    Route::get('/verify', [AuthController::class, 'verify'])->name('auth.verify');
    Route::post('/verify', [AuthController::class, 'verifyPost'])->name('auth.verify.post');
    Route::post('/re-send-otp', [AuthController::class, 'resendMailVerify'])->name('auth.re-send.post');
});

Route::middleware('authenticated')->group(function () {

    Route::get('/connect-api', [PageController::class, 'connectApi'])->name('connect-api');
    Route::get('/listen-api', [PageController::class, 'listenApi']);

    Route::get('/buy-card', [CardController::class, 'buyCard'])->name('buy-card');
    Route::post('/buy-card', [CardController::class, 'buyCardPost'])->name('buy-card.post');
    Route::get('/list-card-buy/{hash}', [CardController::class, 'listCardBuy'])->name('list-card');
    Route::get('/buy-card/history', [CardController::class, 'buyCardHistory'])->name('buy-card.history');

    Route::get('/trade-card', [CardController::class, 'tradeCard'])->name('trade-card');
    Route::post('/trade-card', [CardController::class, 'tradeCardPost'])->name('trade-card.post');
    Route::get('/trade-card/history', [CardController::class, 'tradeCardHistory'])->name('trade-card.history');

    Route::get('/chiet-khau', [CardController::class, 'showDiscount'])->name('chiet-khau');

    Route::get('/recharge', [MoneyController::class, 'recharge'])->name('recharge');

    Route::get('/withdraw', [MoneyController::class, 'withdraw'])->name('withdraw');
    Route::post('/withdraw', [MoneyController::class, 'withdrawPost'])->name('withdraw.post');
    Route::get('/withdraw/history', [MoneyController::class, 'withdrawHistory'])->name('withdraw.history');

    Route::get('/pay-bill', [BillController::class, 'payBill'])->name('pay-bill');
    Route::get('/pay-bill/history', [BillController::class, 'payBillHistory'])->name('pay-bill.history');
    Route::get('/pay-bill/create/{type}', [BillController::class, 'payBillCreate'])->name('pay-bill.create');
    Route::post('/pay-bill/create', [BillController::class, 'payBillCreatePost'])->name('pay-bill.post');

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
Route::post('create/api', [AuthController::class, 'createApiKey'])->name('create_api');
Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'admin.php';
});
