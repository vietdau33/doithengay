<?php

namespace App\Models;

use App\Http\Services\ModelService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        
        $users = User::whereIn('id', $traceUserId)->get()->toArray();
        $users = array_reduce($users, function($result, $user){
            $result[$user['id']] = $user;
            return $result;
        }, []);

        foreach($traces as $key => $trace) {
            if(!in_array($trace['user_id'], $users)) {
                $trace['user'] = [];
            }else{
                $trace['user'] = $users[$trace['user_id']];
            }
            $traces[$key] = $trace;
        }

        return $traces;
    }

    public static function setTrace($mgs, $user_id = null) {
        if($user_id == null) {
            $user_id = auth()->user()->id ?? null;
        }
        if(gettype($mgs) != 'array') {
            $mgs = ['content' => $mgs];
        }
        ModelService::insert(self::class, [
            'user_id' => $user_id,
            'contents' => json_encode($mgs)
        ]);
    }
}
