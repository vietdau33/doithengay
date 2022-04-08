<?php

namespace App\Http\Controllers;

use App\Models\BankModel;
use App\Models\User;
use App\Models\WithdrawModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function home(): Factory|View|Application
    {
        session()->flash('menu-active', 'dashboard');
        return view('admin.home');
    }

    public function changePassword(): Factory|View|Application
    {
        return view('admin.change_password');
    }

    public function withdrawRequest(): Factory|View|Application
    {
        session()->flash('menu-active', 'withdraw-request');
        $lists = WithdrawModel::with('user')->whereIn('status', [0, 1])->orderBy('created_at', 'DESC')->get();
        return view('admin.withdraw.request', compact('lists'));
    }

    public function withdrawHistory(): Factory|View|Application
    {
        session()->flash('menu-active', 'withdraw-history');
        $lists = WithdrawModel::with('user')->whereIn('status', [2, 3])->orderBy('created_at', 'DESC')->get();
        return view('admin.withdraw.history', compact('lists'));
    }

    public function withdrawRequestPost(int $id, int $status): RedirectResponse
    {
        if (!in_array($status, [0, 1, 2, 3], true)) {
            session()->flash('mgs_error', 'Status không chính xác!');
            return redirect()->back();
        }

        $withdraw = WithdrawModel::whereId($id)->first();
        if($withdraw == null){
            session()->flash('mgs_error', 'Yêu cầu không tồn tại!');
            return redirect()->back();
        }

        $user = User::whereId($withdraw->user_id)->first();
        if($user == null){
            session()->flash('mgs_error', 'Người dùng không tồn tại hoặc đã bị xóa!');
            return redirect()->back();
        }

        if($status == 3) {
            $user->money = (int)$user->money + (int)$withdraw->money;
            $user->save();
        }

        $withdraw->status = $status;
        $withdraw->save();

        session()->flash('notif', "Thành công!");
        return redirect()->back();
    }

    public function bankInfo(Request $request): JsonResponse
    {
        $bankId = $request->bank_id ?? null;
        if(empty($bankId)){
            return response()->json([
                'success' => false,
                'message' => "Bank ID empty",
                'datas' => []
            ]);
        }

        $bank = BankModel::whereId($bankId)->first();
        if($bank == null){
            return response()->json([
                'success' => false,
                'message' => "Thông tin thanh toán đã không còn tồn tại!",
                'datas' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Success",
            'datas' => [
                'type' => getTypeBank($bank->type),
                'name' => getNameBank($bank->type, $bank->name),
                'a_number' => $bank->account_number,
                'a_name' => $bank->account_name
            ]
        ]);
    }
}
