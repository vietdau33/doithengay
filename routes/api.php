<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/chiet-khau', [ApiController::class, 'getRate']);
Route::get('/gach-the', [ApiController::class, 'tradeCard']);
Route::get('/mua-the', [ApiController::class, 'buyCard']);
Route::get('/kiem-tra', [ApiController::class, 'checkCard']);
Route::get('/money/plus', [ApiController::class, 'plusMoney']);
