<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Http\Services\BankService;
use App\Models\BankModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{

    public function list(): Factory|View|Application
    {
        $banks = BankModel::whereUserId(user()->id)->get();
        return view('banks.list', compact('banks'));
    }

    public function add(): Factory|View|Application
    {
        return view('banks.add');
    }

    public function addPost(BankRequest $request): RedirectResponse
    {
        $mgs = BankService::addBank($request) ? 'Thêm thẻ thành công!' : 'Thêm thẻ thất bại. Hãy liên hệ admin để kiểm tra!';
        session()->flash('notif', $mgs);
        return redirect()->route('bank.list');
    }

    public function remove($id){
        $bank = BankModel::whereId($id)->first();
        if($bank == null || $bank->user_id != user()->id){
            session()->flash('mgs_error', "Ngân hàng không tồn tại!");
            return back();
        }

        $bank->delete();
        session()->flash('notif', "Đã xóa thành công!");
        return back();
    }
}
