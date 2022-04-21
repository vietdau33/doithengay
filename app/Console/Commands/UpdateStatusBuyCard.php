<?php

namespace App\Console\Commands;

use App\Http\Services\HttpService;
use App\Models\CardStore;
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
        $allCardBuy = CardStore::whereTypeBuy('slow')->whereStatus(0)->get();
        $now = strtotime(Carbon::now());
        foreach ($allCardBuy as $card) {
            if ($now - strtotime($card->created_at) < 300) {
                continue;
            }

            $result = $this->buyCard($card->toArray());

            if ($result === false) {
                $this->refun($card);
                $card->status = 3;
                $card->save();
                continue;
            }

            $card->results = json_encode($result);
            $card->status = 2;
            $card->save();
        }
        return 0;
    }

    private function refun(CardStore $cardStore): void
    {
        $user = User::whereId($cardStore->user_id)->first();
        if ($user == null) {
            return;
        }
        $user->money = (int)$user->money + (int)$cardStore->money_buy;
        $user->save();
    }

    /**
     * @throws GuzzleException
     */
    private function buyCard($param)
    {
        $url = config('card.api.buy');
        $result = HttpService::ins()->post($url, [
            'ApiKey' => env('API_KEY_AUTOCARD', ''),
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
