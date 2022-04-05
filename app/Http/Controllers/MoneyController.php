<?php

namespace App\Http\Controllers;

use App\Http\Requests\WithdrawRequest;
use App\Http\Services\MoneyService;
use App\Models\BankModel;
use App\Models\User;
use App\Models\WithdrawModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MoneyController extends Controller
{
    public function recharge(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-recharge');
        return view('money.recharge');
    }

    public function withdraw(): Factory|View|Application
    {
        $banks = BankModel::whereUserId(user()->id)->get();
        session()->flash('menu-active', 'menu-withdraw');
        return view('money.withdraw', compact('banks'));
    }

    public function withdrawPost(WithdrawRequest $request): RedirectResponse
    {
        $user = User::whereId(user()->id)->first();
        if((int)$request->money > (int)$user->money) {
            session()->flash('mgs_error', 'Số tiền bạn muốn rút lớn hơn số tiền có trong tài khoản. Vui lòng kiểm tra lại số dư và tạo lại yêu cầu mới!');
            return redirect()->back();
        }

        $mgs = MoneyService::withdraw($request) ? 'Tạo yêu cầu rút tiền thành công!' : 'Tạo yêu cầu rút tiền thất bại. Hãy liên hệ admin để giải quyết!';
        session()->flash('notif', $mgs);
        return redirect()->to('/');
    }

    public function withdrawHistory (): Factory|View|Application
    {
        $histories = WithdrawModel::with('bank_relation')->whereUserId(user()->id)->orderBy('created_at', 'DESC')->get();
        return view('money.withdraw_history', compact('histories'));
    }
}
