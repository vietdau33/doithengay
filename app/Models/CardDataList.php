<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardDataList extends Model
{
    use HasFactory;

    protected $table = 'card_data_list';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isActive(): bool
    {
        return $this->card_active === 1;
    }

    public function isUsed(): bool
    {
        return $this->card_used === 1;
    }
}
