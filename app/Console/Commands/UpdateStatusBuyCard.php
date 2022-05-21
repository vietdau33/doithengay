<?php

namespace App\Console\Commands;

use App\Http\Services\HttpService;
use App\Models\CardStore;
use App\Models\ErrorLog;
use App\Models\SystemSetting;
use App\Models\TraceSystem;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateStatusBuyCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-status:buy-card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status Buy Card';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle(): int
    {
        ErrorLog::create([
            'file' => "No thing",
            'error_summary' => 'Update status buy card: ' . date('Y-m-d H:i:s'),
            'log_trace' => strtotime(now())
        ]);

        $allCardBuy = CardStore::whereTypeBuy('slow')->whereIn('status', [0, 99])->get();
        $now = strtotime(Carbon::now());
        foreach ($allCardBuy as $card) {
            if ($card->status === 0 && $now - strtotime($card->created_at) < 300) {
                continue;
            }

            $user = User::whereId($card->user_id)->first();
            if ($user == null) {
                TraceSystem::setTrace([
                    'mgs' => 'Update mua thẻ không thành công, user mua thẻ không còn tồn tại!',
                    'card_id' => $card->id,
                    'user_id' => $card->user_id
                ]);
                continue;
            }

            $result = $this->buyCard($card->toArray());
            $card->change_fast = 1;
            TraceSystem::setTrace([
                'mgs' => 'Chuyển đổi mua chậm sang mua nhanh',
                'card_id' => $card->id
            ]);

            if ($result === false) {
                $card->money_user_before = $user->money;
                $user->money = (int)$user->money + (int)$card->money_after_rate;
                $card->money_user_after = $user->money;
                $card->status = 3;
                TraceSystem::setTrace([
                    'mgs' => 'Mua thẻ nhanh không thành công! Thực hiện refun tiền cho user!',
                    'card_id' => $card->id,
                    'user_id' => $card->user_id,
                    'money_before' => $card->money_user_before,
                    'money_after' => $card->money_user_after,
                ]);
                $user->save();
                $card->save();
                continue;
            }

            $card->results = json_encode($result);
            $card->status = 2;
            $card->save();
        }
        return 0;
    }

    /**
     * @throws GuzzleException
     */
    private function buyCard($param)
    {
        $url = config('card.api.buy');
        $result = HttpService::ins()->post($url, [
            'ApiKey' => SystemSetting::getSetting('api_key_365', 'system', ''),
            'Telco' => ucfirst($param['card_buy']),
            'Amount' => (int)$param['money_buy'],
            'Quantity' => (int)$param['quantity']
        ]);

        if ($result['Code'] === 0) {
            return false;
        }

        return $result['Data'];
    }
}
