<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillModel extends Model
{
    use HasFactory;

    protected $table = 'bill';

    const S_JUST_SEND = 0;
    const S_ACCEPT = 1;
    const S_SUCCESS = 2;
    const S_ERROR = 3;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getStatusHtml($isAdmin = false): string
    {
        $case0 = $isAdmin ? 'Vừa tạo' : 'Đang xử lý';
        $case1 = $isAdmin ? 'Đã xác nhận' : 'Đang xử lý';
        $case2 = $isAdmin ? 'Đã xong' : 'Thành công';
        return match ($this->status) {
            self::S_JUST_SEND => '<span class="text-secondary">' . $case0 . '</span>',
            self::S_ACCEPT => '<span class="text-secondary">' . $case1 . '</span>',
            self::S_SUCCESS => '<span class="text-primary">' . $case2 . '</span>',
            self::S_ERROR => '<span class="text-danger">Từ chối</span>',
            default => '',
        };
    }
}
