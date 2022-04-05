<?php

namespace App\Http\Services;

use App\Http\Requests\TradeCardRequest;
use App\Models\CardStore;
use App\Models\TradeCard;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Str;

class CardService extends Service
{
    public static function getUrlApi(string $type, array $param = []): string
    {
        $url = config("card.api.$type");
        foreach ($param as $key => $data) {
            $url = str_replace("{{$key}}", $data, $url);
        }
        return $url;
    }

    protected static function generate_hash_store(): string
    {
        do {
            $hash = Str::random(32);
        } while (
            CardStore::where('store_hash', $hash)->first() != null
        );
        return $hash;
    }

    protected static function generate_hash_trade(): string
    {
        do {
            $hash = Str::random(32);
        } while (
            TradeCard::whereHash($hash)->first() != null
        );
        return $hash;
    }

    /**
     * @throws GuzzleException
     */
    protected static function get_access_token(): string
    {
        $url = config('card.api_cardvip.authen');
        $param = [
            'username' => env('API_NAPVIP_USERNAME', ''),
            'password' => env('API_NAPVIP_PASSWORD', ''),
            'grant_type' => 'password'
        ];
        $result = HttpService::ins()->post($url, $param, RequestOptions::FORM_PARAMS);
        return $result['access_token'];
    }

    /**
     * @throws GuzzleException
     */
    public static function buyCardPost($request, &$hash = ''): bool
    {
        $param = $request->validated();
        $param['store_hash'] = self::generate_hash_store();
        $param['user_id'] = user()->id;

        $hash = $param['store_hash'];

        if ($param['method_buy'] == CardStore::P_CASH) {
            return self::paymentCash($param);
        }

        return false;
    }

    /**
     * @throws GuzzleException
     */
    protected static function paymentCash($param): bool
    {
        $money = (int)$param['money_buy'] * (int)$param['quantity'];
        $user = User::whereId(user()->id)->first();

        if ((int)$user->money < $money) {
            session()->flash('mgs_error', 'Số tiền có trong tài khoản không đủ. Hãy nạp thêm và thử lại!');
            return false;
        }

        $url = self::getUrlApi('buy');
        $result = HttpService::ins()->post($url, [
            'ApiKey' => env('API_KEY_AUTOCARD', ''),
            'Telco' => ucfirst($param['card_buy']),
            'Amount' => (int)$param['money_buy'],
            'Quantity' => (int)$param['quantity']
        ]);

        if($result['Code'] === 0){
            session()->flash('mgs_error', 'Không thể mua thẻ ngay lúc này. Hãy liên hệ admin để được xử lý!');
            return false;
        }

        $user->money = (int)$user->money - $money;
        $user->save();

        $param['results'] = json_encode($result['Data']);
        return ModelService::insert(CardStore::class, $param) !== false;
    }

    /**
     * @throws GuzzleException
     */
    public static function saveTradeCard(TradeCardRequest $request): bool
    {
        $params = $request->validated();
        $params['user_id'] = user()->id;
        $params['hash'] = self::generate_hash_trade();

        $urlTrade = self::getUrlApi('trade');
        $result = HttpService::ins()->post($urlTrade, [
            'ApiKey' => env('API_KEY_AUTOCARD', ''),
            'Pin' => $params['card_number'],
            'Seri' => $params['card_serial'],
            'CardType' => $params['card_type'],
            'CardValue' => $params['card_money'],
            'requestid' => $params['hash']
        ]);

        if($result['Code'] === 0) {
            session()->flash('mgs_error', $result['Message']);
            return false;
        }

        $params['task_id'] = $result['TaskId'];
        return ModelService::insert(TradeCard::class, $params) !== false;
    }
}
