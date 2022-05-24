<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferMoney extends Model
{
    use HasFactory;

    protected $table = 'transfer_money';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function receive(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_receive', 'id');
    }
}
