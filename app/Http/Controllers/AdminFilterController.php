<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminFilterController extends Controller
{
    public function filterUser(Request $request): JsonResponse
    {
        $account = $request->account;
        $from = $request->filter_from_date;
        $to = $request->filter_to_date;
        $type = $request->type_filter;
        $user = User::whereInactive($type == 'active' ? 0 : 1);
        if(!empty($account)){
            $user->where(function ($query) use ($account) {
                $query
                    ->where('username', $account)
                    ->orWhere('email', $account)
                    ->orWhere('phone', $account);
            });
        }
        $user->where('created_at', '>=', $from . ' 00:00:00');
        $user->where('created_at', '<=', $to . ' 23:59:59');
        $users = $user->get();
        $isActivePage = $type == 'active';
        $html = view('admin.user.table_user', compact('users', 'isActivePage'))->render();
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}
