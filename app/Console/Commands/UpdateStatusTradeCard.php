<?php

namespace App\Console\Commands;

use App\Http\Services\CardService;
use App\Http\Services\HttpService;
use App\Models\ApiCallData;
use App\Models\ErrorLog;
use App\Models\RateCard;
use App\Models\SystemSetting;
use App\Models\TraceSystem;
use App\Models\TradeCard;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ResponseInterface;

class UpdateStatusTradeCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-status:trade-card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status Trade Card';

    /**
     * Execute the console command.
     *
     * @return bool
     * @throws GuzzleException
     */
    public function handle(): bool
    {
        ErrorLog::create([
            'file' => "No thing",
            'error_summary' => 'Update status trade card: ' . date('Y-m-d H:i:s'),
            'log_trace' => strtotime(now())
        ]);

        $this->rates = RateCard::getRate();
        $this->rateID = array_flip(RateCard::getRateId());

        $now = strtotime(Carbon::now());

        $allTrade = TradeCard::whereIn('status', [
            TradeCard::S_JUST_SEND,
            TradeCard::S_WORKING,
            TradeCard::S_PUSH_TO_FAST,
        ])->get();

        $this->info('Start check status trade');
        foreach ($allTrade as $trade) {
            if($trade->status == TradeCard::S_PUSH_TO_FAST) {
                $this->info("Check: $trade->id");
                $this->checkTrade($trade);
                continue;
            }
            if ($trade->type_trade == 'slow' && ($now - strtotime($trade->created_at)) < 300) {
                continue;
            }
            $this->info("Check: $trade->id");
            $this->checkTrade($trade);
        }
        $this->info('Done');
        return 0;
    }

    /**
     * @throws GuzzleException
     */
    public function checkTrade($tradeRecord): bool
    {
        $user = User::whereId($tradeRecord->user_id)->first();
        if ($user == null) {
            TraceSystem::setTrace([
                'mgs' => 'Update đổi thẻ không thành công, user đổi thẻ không còn tồn tại!',
                'trade_id' => $tradeRecord->id,
                'user_id' => $tradeRecord->user_id
            ]);
            return false;
        }

        $taskId = $tradeRecord->task_id;
        if ($taskId == null) {
            $taskId = $this->createTaskId($tradeRecord->toArray());
            if($taskId !== false) {
                $tradeRecord->task_id = $taskId;
            }else{
                $tradeRecord->status = TradeCard::S_ERROR;
            }
            $tradeRecord->save();
            return false;
        }

        $urlCheckTrade = CardService::getUrlApi('check-trade', [
            'taskid' => $taskId
        ]);

        $result = HttpService::ins()->get($urlCheckTrade);
        if (!isset($result['Code'])) {
            return false;
        }

        $tradeRecord->change_fast = 1;
        TraceSystem::setTrace([
            'mgs' => 'Chuyển đổi bán chậm sang bán nhanh',
            'card_id' => $tradeRecord->id
        ]);

        if ($result['Code'] === 0 || $result['Code'] === 1) {
            $tradeRecord->status = TradeCard::S_WORKING;
            $tradeRecord->save();
            return true;
        }

        $hash = $tradeRecord->hash;
        $apiData = ApiCallData::whereHash($hash)->first();
        $paramResponseApi = [
            'hash' => $hash,
            'code' => $tradeRecord->card_number,
            'serial' => $tradeRecord->card_serial,
            'success' => 0,
            'message' => '',
            'amount' => 0,
            'request_id' => $apiData->request_id ?? '',
            'declared_value' => $apiData->amount ?? '',
            'card_value' => 0,
        ];

        if ($result['Code'] === 3) {
            $tradeRecord->status = TradeCard::S_ERROR;
            $tradeRecord->status_card = TradeCard::S_CARD_ERROR;
            $tradeRecord->contents = json_encode($result);
            $tradeRecord->save();

            if($apiData != null) {
                $paramResponseApi['message'] = $result['Message'];
                $this->responseCallbackApi($apiData->callback, $paramResponseApi);
            }

            return true;
        }

        $cardType = $tradeRecord->card_type;
        $typeRate = $this->rateID[$cardType];
        $rate = $this->rates[$typeRate][$tradeRecord->card_money];

        $rateUse = 0;
        if($user->type_user == User::NOMAL) {
            if($tradeRecord->type_trade == 'fast') {
                $rateUse = (float)$rate['rate_use'];
            }else {
                $rateUse = (float)$rate['rate_slow'];
            }
        }elseif($user->type_user == User::DAILY) {
            $rateUse = (float)$rate['rate_daily'];
        }elseif($user->type_user == User::TONGDAILY) {
            $rateUse = (float)$rate['rate_tongdaily'];
        }

        $result['real'] = $result['CardValue'] - ($result['CardValue'] * $rateUse / 100);
        $statusCardHalf = $result['CardValue'] != $result['CardSend'];
        if($statusCardHalf) {
            $result['real'] = $result['real'] / 2;
        }
        $tradeRecord->money_real = $result['real'];
        $tradeRecord->rate_use = $rateUse;

        $tradeRecord->status = TradeCard::S_SUCCESS;
        $tradeRecord->status_card = !$statusCardHalf ? TradeCard::S_CARD_SUCCESS : TradeCard::S_CARD_HALF;
        $tradeRecord->contents = json_encode($result);

        if($apiData != null) {
            $paramResponseApi['message'] = $result['Message'];
            $paramResponseApi['success'] = 1;
            $paramResponseApi['amount'] = $result['real'];
            $paramResponseApi['card_value'] = $result['CardValue'];
            $this->responseCallbackApi($apiData->callback, $paramResponseApi);
        }

        $tradeRecord->money_user_before = $user->money;
        $user->money = (int)$user->money + $result['real'];
        $tradeRecord->money_user_after = $user->money;
        $tradeRecord->save();
        $user->save();

        return true;
    }

    /**
     * @throws GuzzleException
     */
    private function createTaskId($params)
    {
        $urlTrade = config('card.api.trade');
        $result = HttpService::ins()->post($urlTrade, [
            'ApiKey' => SystemSetting::getSetting('api_key_365', 'system', ''),
            'Pin' => $params['card_number'],
            'Seri' => $params['card_serial'],
            'CardType' => $params['card_type'],
            'CardValue' => $params['card_money'],
            'requestid' => $params['hash']
        ]);

        if ($result['Code'] === 0) {
            return false;
        }

        return $result['TaskId'];
    }

    /**
     * @throws GuzzleException
     */
    private function responseCallbackApi($url, $params = []): void
    {
        HttpService::client()->get($url, ['query' => $params]);
    }
}
