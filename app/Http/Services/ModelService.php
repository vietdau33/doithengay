<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ModelService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function insert(string $model, array $datas): mixed
    {
        try {
            !str_contains($model, 'App\Models') && $model = "App\Models\\$model";
            $model = app($model);
            foreach ($datas as $key => $data) {
                $model->{$key} = $data;
            }
            $model->save();
            return $model;
        }catch (\Exception $exception) {
            logger($exception->getMessage());
            return false;
        }
    }

    public static function update(Model $model, array $datas): bool
    {
        try {
            foreach ($datas as $key => $data) {
                $model->{$key} = $data;
            }
            $model->save();
            return true;
        } catch (Exception $exception) {
            logger($exception->getMessage());
            return false;
        }
    }
}
