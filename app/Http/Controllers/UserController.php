<?php

namespace App\Http\Controllers;

use App\Http\Services\OtpService;
use App\Mail\SendOtp;
use App\Models\OtpData;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function securitySetting(): Factory|View|Application
    {
        return view('security.setting');
    }

    public function securityOTPChangeStatus(): JsonResponse
    {
        $result = OtpService::MakeOtp();
        if($result === false){
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi trong lúc tạo mã OTP. Vui lòng thử lại sau!'
            ]);
        }
        try {
            $result['link'] = route('security.callback_security', ['hash' => $result['hash']]);
            Mail::to(user()->email)->send(
                (new SendOtp($result))
                    ->setTemplate('change-status-security-level-2')
                    ->setSubject('OTP thay đổi trạng thái bảo mật cấp 2')
            );
            return response()->json([
                'success' => true,
                'message' => 'Đã gửi OTP qua email. Hãy kiểm tra hộp thư SPAM nếu không thấy trong hộp thư đến!',
                'data' => ['hash' => $result['hash']]
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi trong lúc gửi mã OTP. Vui lòng thử lại sau!'
            ]);
        }
    }

    public function securityChangeStatus(Request $request) {
        if(empty($request->otp_hash) || empty($request->otp_code)){
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa nhập mã OTP!'
            ]);
        }
        if(!OtpData::verify($request->otp_hash, $request->otp_code)){
            session()->flash('mgs_error', 'Mã OTP không khớp!');
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không khớp!'
            ]);
        }
        $user = User::whereId(user()->id)->first();
        $user->security_level_2 = $user->security_level_2 === 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Thay đổi trạng thái bảo mật cấp 2 thành công!',
            'status' => $user->security_level_2
        ]);
    }

    public function callbackChangeStatusSecurity($hash): RedirectResponse
    {
        $otp = OtpData::whereOtpHash($hash)->first();
        if($otp == null) {
            session()->flash('mgs_error', 'Token không tồn tại');
            return redirect()->to('/security/setting');
        }

        if($otp->otp_expire < strtotime(Carbon::now())) {
            $otp->delete();
            session()->flash('mgs_error', 'Token đã hết hạn');
            return redirect()->to('/security/setting');
        }

        $user = User::whereId($otp->user_id)->first();
        if($user == null) {
            session()->flash('mgs_error', 'User không tồn tại!');
            return redirect()->to('/security/setting');
        }

        $user->security_level_2 = $user->security_level_2 === 1 ? 0 : 1;
        $user->save();

        session()->flash('notif', 'Thay đổi trạng thái bảo mật cấp 2 thành công!');
        return redirect()->to('/security/setting');
    }

    public function securitySettingPost(Request $request): JsonResponse
    {
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

    public function sendOtp(): JsonResponse
    {
        $result = OtpService::MakeOtp();
        if($result === false){
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi trong lúc tạo mã OTP. Vui lòng thử lại sau!'
            ]);
        }
        try {
            Mail::to(user()->email)->send(new SendOtp($result));
            return response()->json([
                'success' => true,
                'message' => 'Đã gửi OTP qua email. Hãy kiểm tra hộp thư SPAM nếu không thấy trong hộp thư đến!',
                'data' => ['hash' => $result['hash']]
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi trong lúc gửi mã OTP. Vui lòng thử lại sau!'
            ]);
        }
    }
}
