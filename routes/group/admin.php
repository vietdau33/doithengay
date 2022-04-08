<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', [AdminController::class, 'home'])->name('home');
Route::get('/change-password', [AdminController::class, 'changePassword'])->name('change_password');
Route::get('/withdraw-request', [AdminController::class, 'withdrawRequest'])->name('withdraw-request');
Route::get('/withdraw-request/{id}/{status}', [AdminController::class, 'withdrawRequestPost'])->name('withdraw-request.status');
Route::get('/withdraw-history', [AdminController::class, 'withdrawHistory'])->name('withdraw-history');
Route::post('/bank/info', [AdminController::class, 'bankInfo'])->name('bank.info');
