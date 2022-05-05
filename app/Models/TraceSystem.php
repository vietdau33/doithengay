<?php

namespace App\Models;

use App\Http\Services\ModelService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraceSystem extends Model
{
    use HasFactory;

    protected $table = 'trace_system';

    public static function getTrace(){
        $traces = self::orderBy('created_at', 'DESC')->get()->toArray();

        $traceUserId = array_column($traces, 'user_id');
        $traceUserId = array_filter($traceUserId, function($id){
            return !empty($id);
        });

        if(empty($traceUserId)) {
            return $traces;
        }

        $traceUserId = array_unique($traceUserId);
        $users = User::whereIn('id', $traceUserId)->get()->toArray();
        $users = array_reduce($users, function($result, $user){
            $result[$user['id']] = $user;
            return $result;
        }, []);

        $aryKeyUser = array_keys($users);
        foreach($traces as $key => $trace) {
            if(!in_array($trace['user_id'], $aryKeyUser)) {
                $trace['user'] = [];
            }else{
                $trace['user'] = $users[$trace['user_id']];
            }
            $traces[$key] = $trace;
        }

        return $traces;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function logs(): LengthAwarePaginator
    {
        return self::with('user')->orderBy('created_at', 'DESC')->paginate(20);
    }

    public function getContentsAttribute(){
        return json_decode($this->attributes['contents'], 1);
    }

    public static function setTrace($mgs, $user_id = null) {
        if($user_id == null) {
            $user_id = auth()->user()->id ?? null;
        }
        if(gettype($mgs) != 'array') {
            $mgs = ['mgs' => $mgs];
        }
        ModelService::insert(self::class, [
            'user_id' => $user_id,
            'contents' => json_encode($mgs)
        ]);
    }
}
