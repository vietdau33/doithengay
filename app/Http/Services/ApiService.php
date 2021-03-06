<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Models\RateCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class ApiService extends Service
{

    const API_KEY_NOTFOUND = 100;
    const API_KEY_NOTACTIVE = 101;
    const API_KEY_EXPIRE = 102;
    const API_KEY_NOTSEEOWNER = 103;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function getMgsError ($mgs): string
    {
        return match ($mgs) {
            self::API_KEY_NOTFOUND => 'API Key không tồn tại',
            self::API_KEY_NOTACTIVE => 'API Key chưa được kích hoạt',
            self::API_KEY_EXPIRE => 'API Key đã hết hạn',
            self::API_KEY_NOTSEEOWNER => 'API Key không tìm thấy chủ',
            default => 'Lỗi không xác định'
        };
    }

    public static function getRate(): JsonResponse
    {
        $rates = RateCard::select(['name', 'price', DB::raw('rate_use as rate')])->whereTypeRate('trade')->get()->toArray();
        $rateResult = [];
        foreach ($rates as $rate) {
            $rateResult[$rate['name']][$rate['price']] = $rate;
        }
        return response()->json([
            'success' => 1,
            'datas' => $rateResult
        ]);
    }
}
