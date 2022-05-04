<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function securitySetting(): Factory|View|Application
    {
        return view('security.setting');
    }

    public function securitySettingPost(Request $request){
        $security_level_2 = $request->security_level_2;
        if($security_level_2 == 'true'){
            $security_level_2 = 1;
        }elseif($security_level_2 == 'false'){
            $security_level_2 = 0;
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data không xác định'
            ]);
        }
        $user = User::whereId(user()->id)->first();
        if(empty($user)){
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại'
            ]);
        }
        $user->security_level_2 = $security_level_2;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Thành công!'
        ]);
    }
}
