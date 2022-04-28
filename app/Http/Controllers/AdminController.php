<?php

namespace App\Http\Controllers;

use App\Http\Services\AdminService;
use App\Models\BillModel;
use App\Models\CardListModel;
use App\Models\CardStore;
use App\Models\RateCard;
use App\Models\RateCardSell;
use App\Models\SystemSetting;
use App\Models\TradeCard;
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
        return AdminService::saveStatusWithdraw($id, $status);
    }

    public function bankInfo(Request $request): JsonResponse
    {
        return AdminService::getBankInfoAjax($request->bank_id);
    }

    public function userListActive(): Factory|View|Application
    {
        session()->flash('menu-active', 'user-active');
        $users = User::whereInactive(0)->whereRole('user')->get();
        $action = 'active';
        return view('admin.user.list', compact('users', 'action'));
    }

    public function userListBlock(): Factory|View|Application
    {
        session()->flash('menu-active', 'user-block');
        $users = User::whereInactive(1)->whereRole('user')->get();
        $action = 'block';
        return view('admin.user.list', compact('users', 'action'));
    }

    public function changeActiveUser(int $id, int $status): RedirectResponse
    {
        return AdminService::changeActiveUser($id, $status);
    }

    public function showListBill($type): Factory|View|Application
    {
        session()->flash('menu-active', "pay-bill-$type");
        $bills = BillModel::with('user')->whereType($type)->get();
        return view('admin.bill.list', compact('bills', 'type'));
    }

    public function changeBillStatus(int $id, int $status): RedirectResponse
    {
        return AdminService::changeBillStatus($id, $status);
    }

    public function discount(): Factory|View|Application
    {
        session()->flash('menu-active', 'discount');
        $rates = RateCard::getRate();
        return view('admin.rate.list', compact('rates'));
    }

    public function discountPost($name, Request $request): JsonResponse
    {
        $params = $request->all();
        unset($params['_token']);
        return AdminService::changeRateCard($name, $params);
    }

    public function discountBuy(): Factory|View|Application
    {
        session()->flash('menu-active', 'discount_buy');
        $rates = RateCardSell::getRate();
        return view('admin.rate.buy', compact('rates'));
    }

    public function discountBuyPost($name, Request $request): JsonResponse
    {
        $params = $request->all();
        unset($params['_token']);
        return AdminService::changeRateCardBuy($name, $params);
    }

    public function discountBill(): Factory|View|Application
    {
        session()->flash('menu-active', 'discount_bill');
        $rates = [];
        foreach (RateCard::getRate('bill') as $key => $rate) {
            $key = explode('|', $key);
            $rates[$key[0]][$key[1]] = $rate;
        }
        return view('admin.bill.rate', compact('rates'));
    }

    public function discountBillPost(Request $request): JsonResponse
    {
        $params = $request->all();
        unset($params['_token']);

        return AdminService::changeRateBill($params);
    }

    public function tradeSetting(): Factory|View|Application
    {
        session()->flash('menu-active', 'trade');
        $settings = CardListModel::whereType('trade')->get();
        $type = 'trade';
        return view('admin.feature.setting_status', compact('settings', 'type'));
    }

    public function tradeSettingPost($name, $type): RedirectResponse
    {
        return AdminService::changeStatusCardList($name, $type, 'trade');
    }

    public function buySetting(): Factory|View|Application
    {
        session()->flash('menu-active', 'buy');
        $settings = CardListModel::whereType('buy')->get();
        $type = 'buy';
        return view('admin.feature.setting_status', compact('settings', 'type'));
    }

    public function buySettingPost($name, $type): RedirectResponse
    {
        return AdminService::changeStatusCardList($name, $type, 'buy');
    }

    public function billSetting(): Factory|View|Application
    {
        session()->flash('menu-active', 'bill');
        $settings = CardListModel::whereType('bill')->get();
        $type = 'bill';
        return view('admin.feature.setting_status', compact('settings', 'type'));
    }

    public function billSettingPost($name, $type): RedirectResponse
    {
        return AdminService::changeStatusCardList($name, $type, 'bill');
    }

    public function buyCardRequest(): Factory|View|Application
    {
        session()->flash('menu-active', 'buycard-request');
        $requests = CardStore::with('user')->whereTypeBuy('slow')->whereIn('status', [0, 1])->get();
        return view('admin.buycard.request', compact('requests'));
    }

    public function buyCardRequestSuccess(): Factory|View|Application
    {
        session()->flash('menu-active', 'buycard-request.success');
        $requests = CardStore::with('user')->whereTypeBuy('slow')->whereStatus(2)->get();
        return view('admin.buycard.request', compact('requests'));
    }

    public function buyCardRequestStatus(int $id, int $status): RedirectResponse
    {
        return AdminService::saveStatusBuyCard($id, $status);
    }

    public function tradeCardRequest(): Factory|View|Application
    {
        session()->flash('menu-active', 'tradecard-request');
        $requests = TradeCard::with('user')->whereTypeTrade('slow')->whereIn('status', [1, 2])->get();
        return view('admin.tradecard.request', compact('requests'));
    }

    public function tradeCardRequestSuccess(): Factory|View|Application
    {
        session()->flash('menu-active', 'tradecard-request.success');
        $requests = TradeCard::with('user')->whereTypeTrade('slow')->whereStatus(3)->get();
        return view('admin.tradecard.request', compact('requests'));
    }

    public function tradeCardRequestFail(): Factory|View|Application
    {
        session()->flash('menu-active', 'tradecard-request.fail');
        $requests = TradeCard::with('user')->whereTypeTrade('slow')->whereStatus(4)->get();
        return view('admin.tradecard.request', compact('requests'));
    }

    public function tradeCardRequestStatus(int $id, int $status): RedirectResponse
    {
        return AdminService::saveStatusTradeCard($id, $status);
    }

    public function systemSettings(): Factory|View|Application
    {
        session()->flash('menu-active', 'system-setting');
        $settings = SystemSetting::getAllSetting();
        return view('admin.system_setting.home', compact('settings'));
    }

    public function systemSettingSave(Request $request): RedirectResponse
    {
        $params = $request->only([
            'api_key_365'
        ]);
        AdminService::saveSystemSetting($params);
        session()->flash('notif', "Thay đổi cài đặt thành công!");
        return back();
    }
}
