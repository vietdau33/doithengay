<?php

namespace App\Http\Services;

use App\Http\Requests\BillRequest;
use App\Http\Services\Service;
use App\Models\BillModel;
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
        $param['money'] = implode('', explode(',', $param['money']));

        return ModelService::insert(BillModel::class, $param) !== false;
    }
}
