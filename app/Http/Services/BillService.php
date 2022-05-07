<?php

namespace App\Http\Services;

use App\Http\Requests\BillRequest;
use App\Http\Services\Service;
use App\Models\BillModel;
use App\Models\RateCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BillService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function saveBillRequest(BillRequest $request): bool
    {
        $param = $request->validated();
        $param['user_id'] = user()->id;
        $param['money'] = (int)implode('', explode(',', $param['money']));

        $rateBill = RateCard::getRate('bill');
        $rate = $rateBill[$param['type'].'|'.$param['vendor_id']][0]['rate_use'] ?? 0;
        $param['money_after_rate'] = $param['money'] - $param['money'] * $rate / 100;

        $user = User::whereId(user()->id)->first();

        if ((int)$user->money < $param['money_after_rate']) {
            session()->flash('mgs_error', 'Số tiền có trong tài khoản không đủ. Hãy nạp thêm và thử lại!');
            return false;
        }

        $user->money = (int)$user->money - $param['money_after_rate'];
        $user->save();

        return ModelService::insert(BillModel::class, $param) !== false;
    }
}
