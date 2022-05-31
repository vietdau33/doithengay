<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class TradeCard extends Model
{
    use HasFactory;

    const S_JUST_SEND = 1;
    const S_WORKING = 2;
    const S_SUCCESS = 3;
    const S_ERROR = 4;
    const S_HALF = 5;
    const S_PUSH_TO_FAST = 99;

    const S_CARD_HALF = 3;
    const S_CARD_SUCCESS = 1;
    const S_CARD_ERROR = 2;
    const S_CARD_WORKING = 0;

    protected $table = 'trade_card';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getStatus(): string
    {
        return match ($this->status) {
            self::S_JUST_SEND, self::S_WORKING => 'Đang xử lý',
            self::S_SUCCESS => 'Thành công',
            self::S_ERROR => 'Từ chối',
            default => '',
        };
    }

    public function getStatusHtml(): string
    {
        if($this->status == self::S_ERROR && $this->status_card == self::S_CARD_WORKING) {
            return '<span class="text-danger">Từ chối</span>';
        }
        return match ($this->status_card) {
            self::S_CARD_HALF => '<span class="text-info">Thẻ sai mệnh giá</span>',
            self::S_CARD_SUCCESS => '<span class="text-success">Thẻ đúng</span>',
            self::S_CARD_ERROR => '<span class="text-danger">Thẻ sai</span>',
            default => '<span class="text-secondary">Đang xử lý</span>',
        };
    }

    public function getNameTelco(): string
    {
        static $telcoList;
        if (isset($telcoList[$this->card_type])) {
            return $telcoList[$this->card_type];
        }
        $rate = RateCard::whereRateId($this->card_type)->first();
        $name = ucfirst($rate->name ?? '');
        $telcoList[$this->card_type] = $name;
        return $name;
    }

    public static function getTodayHistory(){
        if(!logined()) {
            return self::whereId(-1)->get();
        }
        $today = Carbon::today()->format('Y-m-d');
        $todayStart = $today . ' 00:00:00';
        $todayEnd = $today . ' 23:59:59';
        return TradeCard::whereUserId(user()->id)
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<=', $todayEnd)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public static function getTotals($dateStart = null, $dateEnd = null, $cardType = null, $userId = null){
        $getAll = $userId != null;
        if(!logined()) {
            return self::whereId(-1)->get();
        }
        if($dateStart == null) {
            $dateStart = Carbon::today()->format('Y-m-d');
        }
        if($dateEnd == null) {
            $dateEnd = Carbon::today()->format('Y-m-d');
        }
        if($userId == null) {
            $userId = user()->id;
        }
        $dateStart = $dateStart . ' 00:00:00';
        $dateEnd = $dateEnd . ' 23:59:59';

        $totals = [];
        $total_template = [
            'card' => 0,
            'money' => 0,
            'success' => 0,
            'success_price' => 0,
            'error' => 0,
            'error_price' => 0,
            'half' => 0,
            'half_price' => 0,
            'pending' => 0,
            'pending_price' => 0,
            'real' => 0
        ];

        $countCard = TradeCard::whereUserId($userId);

        if(!$getAll){
            $countCard
            ->where('created_at', '>=', $dateStart)
            ->where('created_at', '<=', $dateEnd);
        }

        if($cardType != null) {
            $countCard->whereCardType($cardType);
        }

        $countCard = $countCard->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();

        foreach($countCard as $card) {
            $date = date('Y-m-d', strtotime($card['created_at']));
            if(!isset($totals[$date])) {
                $totals[$date] = $total_template;
            }
            $totals[$date]['money'] += $card['valueprices'] ?? 0;
            $totals[$date]['real'] += $card['money_real'] ?? 0;
            $totals[$date]['card'] ++;
            if ($card['type_trade'] == 'fast') {
                switch($card['status_card']){
                    case self::S_CARD_WORKING: 
                        $totals[$date]['pending'] ++;
                        $totals[$date]['pending_price'] += $card['valueprices'];
                        break;
                    case self::S_CARD_SUCCESS: 
                        $totals[$date]['success'] ++;
                        $totals[$date]['success_price'] += $card['valueprices'];
                        break;
                    case self::S_CARD_ERROR: 
                        $totals[$date]['error'] ++;
                        $totals[$date]['error_price'] += $card['card_money'];
                        break;
                    case self::S_CARD_HALF: 
                        $totals[$date]['half'] ++;
                        $totals[$date]['half_price'] += $card['valueprices'];
                        break;
                }
                continue;
            }
            switch($card['status']){
                case self::S_JUST_SEND:
                case self::S_WORKING:
                    $totals[$date]['pending'] ++;
                    $totals[$date]['pending_price'] += $card['valueprices'];
                    break;
                case self::S_SUCCESS: 
                    $totals[$date]['success'] ++;
                    $totals[$date]['success_price'] += $card['valueprices'];
                    break;
                case self::S_ERROR: 
                    $totals[$date]['error'] ++;
                    $totals[$date]['error_price'] += $card['card_money'];
                    break;
                case self::S_HALF: 
                    $totals[$date]['half'] ++;
                    $totals[$date]['half_price'] += $card['valueprices'];
                    break;
            }
        }
        return $totals;
    }
    public static function getTotalAll($userId = null){
        $totals = self::getTotals(null, null, null, $userId);
        $total = [
            'card' => 0,
            'money' => 0,
            'success' => 0,
            'success_price' => 0,
            'error' => 0,
            'error_price' => 0,
            'half' => 0,
            'half_price' => 0,
            'pending' => 0,
            'pending_price' => 0,
            'real' => 0
        ];
        foreach($totals as $tt) {
            foreach($total as $key => $t) {
                $total[$key] += $tt[$key];
            }
        }
        return $total;
    }
}
