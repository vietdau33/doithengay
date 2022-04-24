<?php

namespace App\Http\Controllers;

use App\Http\Services\ApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getRate(): JsonResponse
    {
        return ApiService::getRate();
    }

    public function tradeCard(Request $request) {
        dd($request->all());
    }

    public function buyCard(Request $request) {
        //
    }

    public function checkCard(Request $request) {
        //
    }
}
