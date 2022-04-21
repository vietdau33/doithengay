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
        return match ($this->status) {
            self::S_JUST_SEND, self::S_WORKING => '<span class="text-secondary">Đang xử lý</span>',
            self::S_SUCCESS => '<span class="text-primary">Thành công</span>',
            self::S_ERROR => '<span class="text-danger">Từ chối</span>',
            default => '',
        };
    }

    public function getNameTelco(): string
    {
        static $telcoList;
        if (isset($telcoList[$this->card_type])) {
            return $telcoList[$this->card_type];
        }
        logger('call sql');
        $rate = RateCard::whereRateId($this->card_type)->first();
        $name = ucfirst($rate->name ?? '');
        $telcoList[$this->card_type] = $name;
        return $name;
    }
}
