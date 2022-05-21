<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillRequest;
use App\Http\Services\BillService;
use App\Models\BillModel;
use App\Models\CardListModel;
use App\Models\UserLogs;
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
        $bills = CardListModel::getBillActive();
        return view('bill.list', compact('bills'));
    }

    public function payBillCreate($type): Factory|View|Application|RedirectResponse
    {
        $bills = CardListModel::getBillActive();
        if(!isset($bills[$type])) {
            return redirect()->route('pay-bill');
        }
        $billActive = $bills[$type];
        session()->flash('menu-active', 'menu-pay-bill');
        return view('bill.create', compact('type', 'billActive'));
    }

    public function payBillCreatePost(BillRequest $request): RedirectResponse
    {
        if (!BillService::saveBillRequest($request)) {
            return back()->withInput();
        }
        $typeBill = config('bill')[$request->type];
        session()->flash('notif', 'Đã gửi yêu cầu thanh toán cước.');
        UserLogs::addLogs(
            "Tạo yêu cầu thanh toán cước! Loại '{$typeBill['text']}', nhà mạng '{$typeBill['vendor'][$request->vendor_id]['name']}'",
            'add_bill',
            $request->validated()
        );
        return redirect()->route('pay-bill');
    }

    public function payBillHistory(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-pay-bill');
        $bills = BillModel::whereUserId(user()->id)->orderBy('created_at', 'DESC')->get();
        return view('bill.history', compact('bills'));
    }
}
