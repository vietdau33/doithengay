<?php

namespace App\Console\Commands;

use App\Http\Services\CardService;
use App\Http\Services\HttpService;
use App\Models\RateCard;
use App\Models\TradeCard;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
        $this->rates = RateCard::getRate();
        $this->rateID = array_flip(RateCard::getRateId());

        $status = true;
        $now = strtotime(Carbon::now());
        $allTrade = TradeCard::where(['status' => TradeCard::S_JUST_SEND])->orWhere(['status' => TradeCard::S_WORKING])->get();
        $this->info('Start check status trade');
        foreach ($allTrade as $trade) {
            if ($trade->type_trade == 'slow' && $now - strtotime($trade->created_at) < 300) {
                continue;
            }
            $this->info("Check: $trade->id");
            $status |= $this->checkTrade($trade);
        }
        $this->info('Done');
        return $status;
    }

    /**
     * @throws GuzzleException
     */
    public function checkTrade($tradeRecord): bool
    {
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

        if ($result['Code'] === 0 || $result['Code'] === 1) {
            $tradeRecord->status = TradeCard::S_WORKING;
            $tradeRecord->save();
            return true;
        }

        if ($result['Code'] === 3) {
            $tradeRecord->status = TradeCard::S_ERROR;
            $tradeRecord->contents = json_encode($result);
            $tradeRecord->save();
            return true;
        }

        $cardType = $tradeRecord->card_type;
        $typeRate = $this->rateID[$cardType];
        $rate = $this->rates[$typeRate][$tradeRecord->card_money];
        $devian = (float)$rate['rate_use'] - (float)$rate['rate'];

        $result['real'] = $result['ValueReceive'] - $result['ValueReceive'] * $devian / 100;

        $tradeRecord->status = TradeCard::S_SUCCESS;
        $tradeRecord->contents = json_encode($result);
        $tradeRecord->save();

        $user = User::whereId($tradeRecord->user_id)->first();
        if ($user == null) {
            return false;
        }

        $user->money = (int)$user->money + $result['real'];
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
            'ApiKey' => env('API_KEY_AUTOCARD', ''),
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
}
