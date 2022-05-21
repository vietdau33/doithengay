<?php

namespace App\Http\Services;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Services\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UserService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function changeProfile(ProfileRequest $profileRequest, &$diff = []): bool
    {
        $params = $profileRequest->validated();
        $user = User::whereId(user()->id)->first();
        $diff = array_diff($params, $user->toArray());
        $textAxtract = [
            'fullname' => 'Họ và tên',
            'phone' => 'Số điện thoại',
            'email' => 'Email'
        ];
        foreach ($diff as $k => $d) {
            $diff[$k] = 'Thay ' . $textAxtract[$k] .' từ `' . $user->{$k} . '` sang `' . $d . '`';
        }
        return ModelService::update($user, $params);
    }

    public function changePassword(PasswordRequest $passwordRequest): bool
    {
        $user = User::whereId(user()->id)->first();
        return ModelService::update($user, [
            'password' => bcrypt($passwordRequest->password)
        ]);
    }
}
