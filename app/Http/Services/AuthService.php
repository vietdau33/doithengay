<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\Request;
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
        $user = User::whereUsername($credentials['username'])->first();
        if($user != null && $user->inactive === 1){
            session()->flash('mgs_error', 'Tài khoản đã bị khóa.');
            return false;
        }
        return Auth::attempt($credentials);
    }

    public function registerPost($registerRequest): mixed
    {
        $listKeyUser = array_keys($registerRequest->rules());
        $params = $registerRequest->only($listKeyUser);
        $params['password'] = bcrypt($params['password']);
        return ModelService::insert(User::class, $params);
    }
}
