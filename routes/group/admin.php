<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', [AdminController::class, 'home'])->name('home');
Route::get('/change-password', [AdminController::class, 'changePassword'])->name('change_password');
Route::get('/withdraw-request', [AdminController::class, 'withdrawRequest'])->name('withdraw-request');
Route::get('/withdraw-request/{id}/{status}', [AdminController::class, 'withdrawRequestPost'])->name('withdraw-request.status');
Route::get('/withdraw-history', [AdminController::class, 'withdrawHistory'])->name('withdraw-history');
Route::post('/bank/info', [AdminController::class, 'bankInfo'])->name('bank.info');

Route::prefix('user')->name('user.')->group(function(){
    Route::get('active', [AdminController::class, 'userListActive'])->name('active');
    Route::get('block', [AdminController::class, 'userListBlock'])->name('block');
    Route::get('change-active/{id}/{status}', [AdminController::class, 'changeActiveUser'])->name('change-active');
});

Route::get('bill/{type}', [AdminController::class, 'showListBill'])->name('bill');
Route::get('bill/change-status/{id}/{status}', [AdminController::class, 'changeBillStatus'])->name('bill.change-status');

Route::get('discount', [AdminController::class, 'discount'])->name('discount');
Route::post('discount/change/{name}', [AdminController::class, 'discountPost'])->name('discount.post');
