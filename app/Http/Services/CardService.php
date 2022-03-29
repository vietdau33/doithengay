<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Models\CardStore;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class CardService extends Service
{
    public static function getUrlApi(string $type, array $param = []): string
    {
        $apiKey = env('API_KEY_AUTOCARD', '');
        $url = config("card.api.$type");
        $url = str_replace('{apikey}', $apiKey, $url);
        foreach ($param as $key => $data) {
            $url = str_replace("{{$key}}", $data, $url);
        }
        return $url;
    }

    protected static function generate_hash(): string
    {
        do {
            $hash = Str::random(32);
        } while (
            CardStore::where('store_hash', $hash)->first() != null
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
    public static function buyCardPost($request)
    {
        $param = $request->validated();

        if ($param['method_buy'] == CardStore::P_CASH) {
            $money = (int)$param['money_buy'] * (int)$param['quantity'];
            $user = User::whereId(user()->id)->first();
            if ((int)$user->money < $money) {
                session()->flash('mgs_error', 'Số tiền có trong tài khoản không đủ. Hãy nạp thêm và thử lại!');
                return false;
            }
        }

        $param['store_hash'] = self::generate_hash();
        $param['user_id'] = user()->id;

        ModelService::insert(CardStore::class, $param);

        if ($param['method_buy'] == CardStore::P_CASH) {
            self::paymentCash($param['store_hash']);
        }
    }

    protected static function paymentCash($hash)
    {
        $store = CardStore::whereStoreHash($hash)->first();
        $user = User::whereId($store->user_id)->first();

        $statusBuy = self::CallApiBuyCard($store->card_buy, $store->money_buy, $store->quantity);
    }

    /**
     * @throws GuzzleException
     */
    protected static function CallApiBuyCard($telco, $amount, $quantity)
    {
        $access_key = self::get_access_token();

    }
}
