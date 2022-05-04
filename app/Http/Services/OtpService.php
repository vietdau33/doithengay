<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Models\OtpData;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class OtpService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function MakeOtp($userId = null): bool|array
    {
        self::clearOtp();
        if ($userId == null) {
            $userId = auth()->user()->id ?? null;
        }
        $otpCode = rand(1000000, 9999999);
        $otpHash = sha1(bcrypt($otpCode . now()));
        $result = ModelService::insert(OtpData::class, [
            'user_id' => $userId,
            'otp_hash' => $otpHash,
            'otp_code' => $otpCode,
            'otp_expire' => Carbon::now()->addMinutes(5)
        ]) !== false;
        return !$result ? false : ['code' => $otpCode, 'hash' => $otpHash];
    }

    public static function clearOtp(): void
    {
        foreach (OtpData::where('otp_expire', '<', Carbon::now())->get() as $otp) {
            $otp->delete();
        }
    }
}
