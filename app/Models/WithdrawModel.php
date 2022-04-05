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

    public function getStatus(): string
    {
        return match ($this->status) {
            self::CREATED => 'Vừa tạo',
            self::CONFIRM => 'Đã xác nhận',
            self::SUCCESS => 'Thành công',
            self::CANCEL => 'Từ chối',
            default => '',
        };
    }

    public function bank_relation(): BelongsTo
    {
        return $this->belongsTo(BankModel::class, 'bank', 'id');
    }
}
