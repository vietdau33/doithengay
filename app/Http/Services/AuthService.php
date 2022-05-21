<?php

namespace App\Http\Services;

use App\Models\ApiData;
use App\Models\User;
use App\Models\UserLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function registerPost($registerRequest): bool|ApiData
    {
        $listKeyUser = array_keys($registerRequest->rules());
        $params = $registerRequest->only($listKeyUser);
        $params['password'] = bcrypt($params['password']);
        $user = ModelService::insert(User::class, $params);
        if($user === false) {
            return false;
        }
        UserLogs::addLogs('Đăng ký tài khoản!', UserLogs::REGISTER, ['user_id' => $user->id]);
        return ApiData::createAPI($user->id);
    }
}
