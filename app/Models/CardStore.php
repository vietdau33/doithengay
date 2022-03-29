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
}
