<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AuthService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function loginPost($loginRequest): bool
    {
        $credentials = $loginRequest->only('username', 'password');
        return Auth::attempt($credentials);
    }

    public function registerPost($registerRequest): mixed
    {
        $listKeyUser = array_keys($registerRequest->rules());
        $params = $registerRequest->only($listKeyUser);
        $params['password'] = bcrypt($params['password']);
        return ModelService::insert('User', $params);
    }
}
