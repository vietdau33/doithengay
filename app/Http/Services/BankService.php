<?php

namespace App\Http\Services;

use App\Http\Requests\BankRequest;
use App\Http\Services\Service;
use App\Models\BankModel;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class BankService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function addBank(BankRequest $request): bool
    {
        $params = $request->validated();
        list($type, $name) = explode('_', $params['bank_select']);
        $aryInsert = [
            'type' => $type,
            'name' => $name,
            'account_number' => $params['account_number'],
            'account_name' => $params['account_name'],
            'user_id' => user()->id
        ];
        return ModelService::insert(BankModel::class, $aryInsert) !== false;
    }
}
