<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', [AdminController::class, 'home'])->name('home');
Route::get('/change-password', [AdminController::class, 'changePassword'])->name('change_password');
Route::get('/withdraw-request', [AdminController::class, 'withdrawRequest'])->name('withdraw-request');
Route::get('/withdraw-request/{id}/{status}', [AdminController::class, 'withdrawRequestPost'])->name('withdraw-request.status');
Route::get('/withdraw-history', [AdminController::class, 'withdrawHistory'])->name('withdraw-history');
Route::post('/bank/info', [AdminController::class, 'bankInfo'])->name('bank.info');

Route::prefix('/buy-card')->group(function() {
    Route::get('/request', [AdminController::class, 'buyCardRequest'])->name('buycard-request');
    Route::get('/request/success', [AdminController::class, 'buyCardRequestSuccess'])->name('buycard-request.success');
    Route::get('/request/{id}/{status}', [AdminController::class, 'buyCardRequestStatus'])->name('buycard-request.status');
});

Route::prefix('/trade-card')->group(function() {
    Route::get('/request', [AdminController::class, 'tradeCardRequest'])->name('tradecard-request');
    Route::get('/request/success', [AdminController::class, 'tradeCardRequestSuccess'])->name('tradecard-request.success');
    Route::get('/request/fail', [AdminController::class, 'tradeCardRequestFail'])->name('tradecard-request.fail');
    Route::get('/request/{id}/{status}', [AdminController::class, 'tradeCardRequestStatus'])->name('tradecard-request.status');
});

Route::prefix('user')->name('user.')->group(function(){
    Route::get('active', [AdminController::class, 'userListActive'])->name('active');
    Route::get('block', [AdminController::class, 'userListBlock'])->name('block');
    Route::get('change-active/{id}/{status}', [AdminController::class, 'changeActiveUser'])->name('change-active');
});

Route::get('bill/{type}', [AdminController::class, 'showListBill'])->name('bill');
Route::get('bill/change-status/{id}/{status}', [AdminController::class, 'changeBillStatus'])->name('bill.change-status');

Route::prefix('feature')->name('feature.')->group(function() {
    //discount
    Route::get('discount', [AdminController::class, 'discount'])->name('discount');
    Route::post('discount/change/{name}', [AdminController::class, 'discountPost'])->name('discount.post');

    //discount bill
    Route::get('discount_bill', [AdminController::class, 'discountBill'])->name('discount_bill');
    Route::post('discount_bill/change', [AdminController::class, 'discountBillPost'])->name('discount_bill.post');

    //trade
    Route::get('trade', [AdminController::class, 'tradeSetting'])->name('trade');
    Route::get('trade/{name}/{type}', [AdminController::class, 'tradeSettingPost'])->name('trade.post');

    //buy
    Route::get('buy', [AdminController::class, 'buySetting'])->name('buy');
    Route::get('buy/{name}/{type}', [AdminController::class, 'buySettingPost'])->name('buy.post');

    //bill
    Route::get('bill', [AdminController::class, 'billSetting'])->name('bill');
    Route::get('bill/{name}/{type}', [AdminController::class, 'billSettingPost'])->name('bill.post');
});
