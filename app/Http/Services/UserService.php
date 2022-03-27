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

    public function changeProfile(ProfileRequest $profileRequest): bool
    {
        $listKeyUser = array_keys($profileRequest->rules());
        $params = $profileRequest->only($listKeyUser);
        $user = User::whereId(user()->id)->first();
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
