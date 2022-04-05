<?php

namespace App\Console\Commands;

use App\Http\Services\CardService;
use App\Http\Services\HttpService;
use App\Models\TradeCard;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

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
        $status = true;
        $allTrade = TradeCard::where(['status' => TradeCard::S_JUST_SEND])->orWhere(['status' => TradeCard::S_WORKING])->get();
        $this->info('Start check status trade');
        foreach ($allTrade as $trade) {
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
            return false;
        }

        $urlCheckTrade = CardService::getUrlApi('check-trade', [
            'taskid' => $taskId
        ]);

        $result = HttpService::ins()->get($urlCheckTrade);
        if (!isset($result['Code'])) {
            return false;
        }

        if($result['Code'] === 0 || $result['Code'] === 1) {
            $tradeRecord->status = TradeCard::S_WORKING;
            $tradeRecord->save();
            return true;
        }

        if($result['Code'] === 3) {
            $tradeRecord->status = TradeCard::S_ERROR;
            $tradeRecord->contents = json_encode($result);
            $tradeRecord->save();
            return true;
        }

        $result['real'] = $result['ValueReceive'] - $result['ValueReceive'] * config('card.rate-compare') / 100;

        $tradeRecord->status = TradeCard::S_SUCCESS;
        $tradeRecord->contents = json_encode($result);
        $tradeRecord->save();

        $user = User::whereId($tradeRecord->user_id)->first();
        if($user == null) {
            return false;
        }

        $user->money = (int)$user->money + $result['real'];
        $user->save();

        return true;
    }
}
