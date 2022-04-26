<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeCard extends Model
{
    use HasFactory;

    const S_JUST_SEND = 1;
    const S_WORKING = 2;
    const S_SUCCESS = 3;
    const S_ERROR = 4;
    const S_PUSH_TO_FAST = 99;

    const S_CARD_ERROR = 3;
    const S_CARD_SUCCESS = 1;
    const S_CARD_HALF = 2;
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
}
