<?php

namespace App\Http\Controllers;

use App\Http\Services\ApiService;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function getRate(): JsonResponse
    {
        return ApiService::getRate();
    }
}
