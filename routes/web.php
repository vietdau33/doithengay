<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\MoneyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CardController;

//Route::get('/logs', [PageController::class, 'showLogs'])->name('logger');
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/maintenance', function () {
    return view('maintenance');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/comming-soon', [PageController::class, 'commingSoon'])->name('comming-soon');
Route::get('/check-rate', [CardController::class, 'checkRate'])->name('check-rate');
Route::post('/administator/plus-money', [MoneyController::class, 'plusMoneyUser'])->name('plus-money');
Route::get('/auth/verify-link/{hash}', [AuthController::class, 'userVerifyHash']);
Route::post('create/api', [AuthController::class, 'createApiKey'])->name('create_api');

Route::middleware('guest')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('view');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('post');

    Route::get('/register', [AuthController::class, 'register'])->name('register.view');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

    Route::prefix('forgot-password')->name('forgot.')->group(function () {
        Route::get('', [AuthController::class, 'forgot'])->name('view');
        Route::get('{hash}', [AuthController::class, 'forgotVerify']);
        Route::post('', [AuthController::class, 'forgotPost'])->name('post');
    });
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
    Route::post('/buy-card/multi', [CardController::class, 'buyCardMulti'])->name('buy-card-multi.post');
    Route::get('/list-card-buy/{hash}', [CardController::class, 'listCardBuy'])->name('list-card');
    Route::get('/buy-card/history', [CardController::class, 'buyCardHistory'])->name('buy-card.history');

    Route::prefix('trade-card')->group(function () {
        Route::get('', [CardController::class, 'tradeCard'])->name('trade-card');
        Route::post('', [CardController::class, 'tradeCardPost'])->name('trade-card.post');
        Route::get('/history', [CardController::class, 'tradeCardHistory'])->name('trade-card.history');
    });

    Route::get('/chiet-khau', [CardController::class, 'showDiscount'])->name('chiet-khau');

    Route::get('/recharge', [MoneyController::class, 'recharge'])->name('recharge');

    Route::prefix('withdraw')->group(function () {
        Route::get('/', [MoneyController::class, 'withdraw'])->name('withdraw');
        Route::post('/', [MoneyController::class, 'withdrawPost'])->name('withdraw.post');
        Route::get('/history', [MoneyController::class, 'withdrawHistory'])->name('withdraw.history');
    });

    Route::prefix('pay-bill')->group(function () {
        Route::get('', [BillController::class, 'payBill'])->name('pay-bill');
        Route::get('/history', [BillController::class, 'payBillHistory'])->name('pay-bill.history');
        Route::get('/create/{type}', [BillController::class, 'payBillCreate'])->name('pay-bill.create');
        Route::post('/create', [BillController::class, 'payBillCreatePost'])->name('pay-bill.post');
    });

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

    Route::prefix('security')->name('security.')->group(function () {
        Route::get('setting', [UserController::class, 'securitySetting'])->name('setting');
        Route::post('setting/otp-change-status', [UserController::class, 'securityOTPChangeStatus'])->name('otp_change_status');
        Route::post('setting/change-status', [UserController::class, 'securityChangeStatus'])->name('change_status');
        Route::get('setting/callback-security/{hash}', [UserController::class, 'callbackChangeStatusSecurity'])->name('callback_security');
        Route::post('setting/security_level_2', [UserController::class, 'securitySettingPost']);
        Route::post('send-otp', [UserController::class, 'sendOtp'])->name('send-otp');
    });

    Route::prefix('transfer')->name('transfer.')->group(function () {
        Route::get('', [MoneyController::class, 'transfer'])->name('home');
        Route::post('get-name-user', [MoneyController::class, 'transferGetUserFulleName'])->name('get-user-name');
        Route::post('', [MoneyController::class, 'transferPost'])->name('post');
    });
});

Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'group' . DIRECTORY_SEPARATOR . 'admin.php';
});
