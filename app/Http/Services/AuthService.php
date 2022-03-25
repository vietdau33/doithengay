<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AuthService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function verify_login()
    {
        return true;
    }

    public function registerPost($registerRequest): mixed
    {
        $listKeyUser = array_keys($registerRequest->rules());
        $params = $registerRequest->only($listKeyUser);
        $params['password'] = bcrypt($params['password']);
        return ModelService::insert('User', $params);
    }
}
