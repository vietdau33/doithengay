<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillRequest;
use App\Http\Services\BillService;
use App\Models\BillModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function payBill(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-pay-bill');
        return view('bill.list');
    }

    public function payBillCreate($type): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-pay-bill');
        return view('bill.create', compact('type'));
    }

    public function payBillCreatePost(BillRequest $request): RedirectResponse
    {
        if (!BillService::saveBillRequest($request)) {
            return back()->withInput();
        }
        session()->flash('notif', 'Đã gửi yêu cầu! Hãy kiểm tra lịch sử để xem trạng thái gạch thẻ.');
        return redirect()->route('pay-bill');
    }

    public function payBillHistory(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-pay-bill');
        $bills = BillModel::whereUserId(user()->id)->get();
        return view('bill.history', compact('bills'));
    }
}
