<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ModelService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function insert(string $model, array $datas): mixed
    {
        $model = app("App\Models\\$model");
        foreach ($datas as $key => $data) {
            $model->{$key} = $data;
        }
        $model->save();
        return $model;
    }
}
