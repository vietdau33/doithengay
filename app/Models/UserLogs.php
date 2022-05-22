<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLogs extends Model
{
    use HasFactory;

    const LOGIN = 'login';
    const REGISTER = 'register';
    const VERIFY = 'verify';
    const FORGOT_PASSWORD = 'forgot_password';

    protected $table = 'user_logs';
    protected $casts = [
        'contents' => 'json'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function addLogs(string $mgs, string $typeLog = 'unknow', array $contents = []): UserLogs
    {
        $log = new self;
        $log->ip = getIpPublic();
        $log->user_id = $contents['user_id'] ?? (user()->id ?? 0);
        $log->type_log = $typeLog;
        $log->message = $mgs;
        $log->contents = $contents;
        $log->save();
        return $log->refresh();
    }

    public static function getLogs() {
        return self::whereUserId(user()->id)->orderBy('created_at', 'DESC')->paginate(10);
    }
}
