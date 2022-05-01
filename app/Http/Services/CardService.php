<?php

namespace App\Http\Services;

use App\Http\Requests\TradeCardRequest;
use App\Models\CardListModel;
use App\Models\CardStore;
use App\Models\RateCard;
use App\Models\RateCardSell;
use App\Models\SystemSetting;
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
        $listNotAuto = CardListModel::whereAuto('0')->whereType('buy')->get()->toArray();
        $listNotAuto = array_column($listNotAuto, 'name');
        if(in_array($param['card_buy'], $listNotAuto)) {
            session()->flash('mgs_error', 'Mua nhanh hiện không khả dụng!');
            return false;
        }

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
        $rate = RateCardSell::getRateSingle($param['card_buy'], (int)$param['money_buy']);

        $rate = $param['type_buy'] == 'fast' ? (float)($rate->rate ?? 0) : (float)($rate->rate_slow ?? 0);
        $money = (int)$param['money_buy'] * (int)$param['quantity'];
        $money -= $money * $rate / 100;

        $user = User::whereId(user()->id)->first();

        if ((int)$user->money < $money) {
            session()->flash('mgs_error', 'Số tiền có trong tài khoản không đủ. Hãy nạp thêm và thử lại!');
            return false;
        }

        if($param['type_buy'] == 'fast') {
            $url = self::getUrlApi('buy');
            $result = HttpService::ins()->post($url, [
                'ApiKey' => SystemSetting::getSetting('api_key_365', 'system', ''),
                'Telco' => ucfirst($param['card_buy']),
                'Amount' => (int)$param['money_buy'],
                'Quantity' => (int)$param['quantity']
            ]);

            if($result['Code'] === 0){
                session()->flash('mgs_error', 'Không thể mua thẻ ngay lúc này. Hãy liên hệ admin để được xử lý!');
                $param['rate_buy'] = 0;
                $param['money_after_rate'] = 0;
                $param['money_user_before'] = user()->money;
                $param['status'] =CardStore::S_CANCEL;
                ModelService::insert(CardStore::class, $param);
                return false;
            }

            $param['results'] = json_encode($result['Data']);
            $param['status'] =CardStore::S_SUCCESS;
        }

        $param['money_user_before'] = $user->money;

        $user->money = (int)$user->money - $money;
        $user->save();

        $param['rate_buy'] = $rate;
        $param['money_after_rate'] = $money;
        $param['money_user_after'] = $user->money;

        return ModelService::insert(CardStore::class, $param) !== false;
    }

    /**
     * @throws GuzzleException
     */
    public static function saveTradeCardFast(TradeCardRequest $request): bool
    {
        $params = $request->validated();
        $params['user_id'] = user()->id;
        $params['hash'] = self::generate_hash_trade();

        $cardType = RateCard::whereName($params['card_type'])->first();
        if($cardType == null) {
            session()->flash('Nhà mạng không tồn tại!');
            return false;
        }

        $params['card_type'] = $cardType->rate_id;

        $urlTrade = self::getUrlApi('trade');
        $result = HttpService::ins()->post($urlTrade, [
            'ApiKey' => SystemSetting::getSetting('api_key_365', 'system', ''),
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
        $params['money_user_before'] = user()->money;
        return ModelService::insert(TradeCard::class, $params) !== false;
    }

    public static function saveTradeCardSlow(TradeCardRequest $request): bool
    {
        $params = $request->validated();
        $params['user_id'] = user()->id;
        $params['hash'] = self::generate_hash_trade();

        $cardType = RateCard::whereName($params['card_type'])->first();
        if($cardType == null) {
            session()->flash('Nhà mạng không tồn tại!');
            return false;
        }

        $params['card_type'] = $cardType->rate_id;
        $params['money_user_before'] = user()->money;
        return ModelService::insert(TradeCard::class, $params) !== false;
    }

    /**
     * @throws GuzzleException
     */
    public static function get_rate_card(): bool
    {
        $urlCheckRate = CardService::getUrlApi('rate', [
            'apikey' => SystemSetting::getSetting('api_key_365', 'system', '')
        ]);

        $result = HttpService::ins()->get($urlCheckRate);

        if($result['Code'] !== 1){
            return false;
        }

        foreach ($result['Data'] as $rate) {
            $name = strtolower($rate['name']);
            $id = $rate['id'];
            foreach ($rate['prices'] as $price) {
                $_p = $price['price'];
                $rate = $price['rate'];
                $rateCard = RateCard::whereName($name)->wherePrice($_p)->first();
                if($rateCard != null) {
                    $rateCard->rate = $rate;
                    $rateCard->rate_id = $id;
                    $rateCard->type_rate = 'trade';
                    $rateCard->save();
                    continue;
                }
                ModelService::insert(RateCard::class, [
                    'name' => $name,
                    'rate_id' => $id,
                    'price' => $_p,
                    'rate' => $rate,
                    'rate_use' => $rate,
                    'type_rate' => 'trade'
                ]);
            }
        }

        return true;
    }
}
