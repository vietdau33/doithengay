<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class OtpData extends Model
{
    use HasFactory;

    protected $table = 'otp_data';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function verify($hash, $otp, $removeIfSuccess = true): bool
    {
        $otp = self::whereOtpHash($hash)->whereOtpCode($otp)->where('otp_expire', '<=', Carbon::now())->first();
        if (empty($otp)) {
            return false;
        }
        if ($removeIfSuccess) {
            $otp->delete();
        }
        return true;
    }
}
