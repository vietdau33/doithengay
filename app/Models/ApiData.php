<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class ApiData extends Model
{
    use HasFactory;

    protected $table = 'api_data';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function createAPI($userId, $name = 'API Default'): ApiData
    {
        $now = strtotime(now());
        $self = new self;
        $self->user_id = $userId;
        $self->api_name = $name;
        $self->api_key = sha1(Hash::make($now));
        $self->api_expire = $now;
        $self->save();
        return $self;
    }
}
