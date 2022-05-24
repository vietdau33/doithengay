<?php

namespace App\Http\Controllers;

use App\Http\Services\ApiService;
use App\Http\Services\CardService;
use App\Http\Services\HttpService;
use App\Http\Services\ModelService;
use App\Models\ApiCallData;
use App\Models\ApiData;
use App\Models\RateCard;
use App\Models\SystemSetting;
use App\Models\TraceSystem;
use App\Models\TradeCard;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function getRate(): JsonResponse
    {
        return ApiService::getRate();
    }

    /**
     * @throws GuzzleException
     */
    public function tradeCard(Request $request): JsonResponse
    {
        $aryKey = ['telco', 'code', 'serial', 'type', 'amount', 'request_id', 'callback'];
        $aryKeyRequest = array_keys($request->all());

        foreach ($aryKey as $key) {
            if (!in_array($key, $aryKeyRequest)) {
                return $this->returnFail('Tham số đầu vào chưa đủ. Thiếu: ' . $key);
            }
        }

        if (!in_array($request->type, ['slow', 'fast'])) {
            return $this->returnFail('Loại gạch thẻ không chính xác!');
        }

        $rates = RateCard::getListCardTrade();
        $cardList = array_reduce($rates, function ($result, $card) {
            $card = end($card);
            $result[] = strtoupper($card['name']);
            return $result;
        }, []);

        if (!in_array($request->telco, $cardList)) {
            return $this->returnFail('Telco bạn gửi lên không được hỗ trợ. Đang hỗ trợ những telco sau: ' . implode(', ', $cardList));
        }

        $lengthCardNumber = strlen($request->code);
        switch (strtoupper($request->telco)) {
            case 'VIETTEL':
                if($lengthCardNumber != 13 && $lengthCardNumber != 15) {
                    return $this->returnFail('Định dạng thẻ không đúng!');
                }
                break;
            case 'VINAPHONE':
                if($lengthCardNumber != 12 && $lengthCardNumber != 14) {
                    return $this->returnFail('Định dạng thẻ không đúng!');
                }
                break;
            case 'MOBIFONE':
            case 'VIETNAMOBILE':
                if($lengthCardNumber != 12) {
                    return $this->returnFail('Định dạng thẻ không đúng!');
                }
                break;
        }

        if (TradeCard::whereCardNumber($request->code)->first() != null) {
            return $this->returnFail('Mã thẻ đã tồn tại trên hệ thống!');
        }

        $hash = $this->generate_hash_trade();
        $params = [
            'user_id' => $request->user->id,
            'card_type' => strtolower($request->telco),
            'card_money' => $request->amount,
            'card_serial' => $request->serial,
            'card_number' => $request->code,
            'hash' => $hash
        ];

        if($request->type == 'slow') {
            $mgs = $this->tradeCardSlow($params);
        }else {
            $mgs = $this->tradeCardFast($params);
        }

        $allRequest = $request->all();
        $allRequest['hash'] = $hash;
        ModelService::insert(ApiCallData::class, $allRequest);

        if($mgs === true){
            TraceSystem::setTrace([
                'mgs' => 'API đổi thẻ!',
                ...$request->all()
            ]);
        }

        return $mgs === true ? $this->returnSuccess($hash) : $this->returnFail($mgs);
    }

    private function tradeCardSlow($params): string|bool
    {
        $cardType = RateCard::whereName(strtolower($params['card_type']))->first();
        if ($cardType == null) {
            return 'Nhà mạng không tồn tại!';
        }

        $params['card_type'] = $cardType->rate_id;
        $params['type_call'] = 'api';
        $params['type_trade'] = 'slow';
        if (ModelService::insert(TradeCard::class, $params) === false) {
            return 'Gạch thẻ không thành công!';
        }

        return true;
    }

    /**
     * @throws GuzzleException
     */
    private function tradeCardFast($params): string|bool
    {
        $cardType = RateCard::whereName($params['card_type'])->first();
        if ($cardType == null) {
            return 'Nhà mạng không tồn tại!';
        }

        $params['card_type'] = $cardType->rate_id;

        $urlTrade = CardService::getUrlApi('trade');
        $result = HttpService::ins()->post($urlTrade, [
            'ApiKey' => SystemSetting::getSetting('api_key_365', 'system', ''),
            'Pin' => $params['card_number'],
            'Seri' => $params['card_serial'],
            'CardType' => $params['card_type'],
            'CardValue' => $params['card_money'],
            'requestid' => $params['hash']
        ]);

        if($result['Code'] === 0) {
            return $result['Message'];
        }

        $params['task_id'] = $result['TaskId'];
        $params['type_call'] = 'api';
        $params['type_trade'] = 'fast';
        if(ModelService::insert(TradeCard::class, $params) === false) {
            return 'Gạch thẻ không thành công!';
        }

        return true;
    }

    private function generate_hash_trade(): string
    {
        do {
            $hash = Str::random(32);
        } while (
            TradeCard::whereHash($hash)->first() != null
        );
        return $hash;
    }

    public function buyCard(Request $request)
    {
        //
    }

    public function checkCard(Request $request): JsonResponse
    {
        if($request->hash == null) {
            return $this->returnFail('Hash không được truyền lên!');
        }

        $tradeCard = TradeCard::whereHash($request->hash)->first();
        $apiData = ApiCallData::whereHash($request->hash)->first();
        if($tradeCard == null || $apiData == null) {
            return $this->returnFail('Hash không tồn tại!');
        }

        if($tradeCard->contents == null) {
            return $this->returnFail('Phiên gạch thẻ chưa xử lý xong hoặc chưa được xử lý!');
        }

        $contents = json_decode($tradeCard->contents, 1);

        return response()->json([
            'hash' => $request->hash,
            'code' => $tradeCard->card_number,
            'serial' => $tradeCard->card_serial,
            'success' => (int)($tradeCard->status === 3),
            'message' => $contents['Message'],
            'amount' => $contents['real'] ?? 0,
            'request_id' => $apiData->request_id ?? '',
            'declared_value' => $apiData->amount ?? '',
            'card_value' => $contents['CardValue'],
        ]);
    }

    private function returnFail($mgs): JsonResponse
    {
        return response()->json([
            'success' => 0,
            'message' => $mgs,
            'hash' => ''
        ]);
    }

    private function returnSuccess($hash): JsonResponse
    {
        return response()->json([
            'success' => 1,
            'message' => 'Thành công',
            'hash' => $hash
        ]);
    }

    public function plusMoney(Request $request): JsonResponse
    {
        return $this->returnSuccess('ds');
    }
}
