<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawModel extends Model
{
    use HasFactory;

    const CREATED = 0;
    const CONFIRM = 1;
    const SUCCESS = 2;
    const CANCEL = 3;

    protected $table = 'withdraw';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getStatus(): string
    {
        return match ($this->status) {
            self::CREATED => '<span class="text-secondary">Vừa tạo</span>',
            self::CONFIRM => '<span class="text-primary">Đã xác nhận</span>',
            self::SUCCESS => '<span class="text-success">Thành công</span>',
            self::CANCEL => '<span class="text-danger">Từ chối</span>',
            default => '',
        };
    }

    public function bank_relation(): BelongsTo
    {
        return $this->belongsTo(BankModel::class, 'bank', 'id');
    }

    public static function getHistoryWithdraw(){
        return self::with('bank_relation')
            ->whereUserId(user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public static function getHistoryWithdrawWithTime($from, $to){
        return self::with('bank_relation')
            ->whereUserId(user()->id)
            ->where('created_at', '>=', $from . ' 00:00:00')
            ->where('created_at', '<=', $to . ' 23:59:59')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
