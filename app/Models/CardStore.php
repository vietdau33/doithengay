<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardStore extends Model
{
    use HasFactory;

    const P_CASH = 'cash';
    const S_CREATE = 0;
    const S_ACCEPT = 1;
    const S_SUCCESS = 2;
    const S_CANCEL = 3;

    protected $table = 'card_store';

    public function getStatus(): string
    {
        if ($this->type_buy == 'fast') {
            return '<span class="text-success">Thành công</span>';
        }
        return match ($this->status) {
            self::S_CREATE => '<span class="text-secondary">Chờ xác nhận</span>',
            self::S_ACCEPT => '<span class="text-secondary">Đã xác nhận</span>',
            self::S_SUCCESS => '<span class="text-success">Thành công</span>',
            self::S_CANCEL => '<span class="text-danger">Từ chối</span>',
            default => ''
        };
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
