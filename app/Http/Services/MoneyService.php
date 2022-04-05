<?php

namespace App\Http\Services;

use App\Http\Requests\WithdrawRequest;
use App\Models\User;
use App\Models\WithdrawModel;
use Illuminate\Http\Request;

class MoneyService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function withdraw(WithdrawRequest $request): bool
    {
        $param = $request->validated();
        $param['user_id'] = user()->id;
        $status = ModelService::insert(WithdrawModel::class, $param) !== false;
        if(!$status){
            return false;
        }
        $user = User::whereId(user()->id)->first();
        $user->money = (int)$user->money - (int)$param['money'];
        $user->save();
        return true;
    }
}
