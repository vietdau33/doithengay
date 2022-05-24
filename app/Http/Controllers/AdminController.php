<?php

namespace App\Http\Controllers;

use App\Http\Requests\SystemBankRequest;
use App\Http\Services\AdminService;
use App\Http\Services\ModelService;
use App\Models\BankMessenger;
use App\Models\BillModel;
use App\Models\CardListModel;
use App\Models\CardStore;
use App\Models\Notification;
use App\Models\RateCard;
use App\Models\RateCardSell;
use App\Models\Report;
use App\Models\SystemBank;
use App\Models\SystemSetting;
use App\Models\TraceSystem;
use App\Models\TradeCard;
use App\Models\User;
use App\Models\UserLogs;
use App\Models\WithdrawModel;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected int $paginateLog = 5;

    public function home(): Factory|View|Application
    {
        session()->flash('menu-active', 'dashboard');
        $reports = Report::getReports();
        return view('admin.home', compact('reports'));
    }

    public function changePassword(): Factory|View|Application
    {
        return view('admin.change_password');
    }

    public function withdrawRequest(): Factory|View|Application
    {
        session()->flash('menu-active', 'withdraw-request');
        $dateNow = date('Y-m-d');
        $lists = WithdrawModel::with('user')->whereIn('status', [0, 1]);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $lists->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $lists->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $lists->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $lists->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $lists->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $lists = $lists->orderBy('created_at', 'DESC')->get();
        return view('admin.withdraw.request', compact('lists'));
    }

    public function withdrawHistory(): Factory|View|Application
    {
        session()->flash('menu-active', 'withdraw-history');
        $dateNow = date('Y-m-d');
        $lists = WithdrawModel::with('user')->whereIn('status', [2, 3]);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $lists->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $lists->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $lists->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $lists->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $lists->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $lists = $lists->orderBy('created_at', 'DESC')->get();
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
        $dateNow = date('Y-m-d');
        $bills = BillModel::with('user')->whereType($type);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $bills->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $bills->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $bills->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $bills->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $bills->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $bills = $bills->orderBy('created_at', 'DESC')->get();
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
        $dateNow = date('Y-m-d');
        $requests = CardStore::with('user')->whereTypeBuy('slow')->whereIn('status', [0, 1]);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $requests->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $requests->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $requests->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $requests->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $requests->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $requests = $requests->orderBy('created_at', 'DESC')->get();
        return view('admin.buycard.request', compact('requests'));
    }

    public function buyCardRequestSuccess(): Factory|View|Application
    {
        session()->flash('menu-active', 'buycard-request.success');
        $dateNow = date('Y-m-d');
        $requests = CardStore::with('user')->whereTypeBuy('slow')->whereStatus(2);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $requests->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $requests->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $requests->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $requests->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $requests->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $requests = $requests->orderBy('created_at', 'DESC')->get();
        return view('admin.buycard.request', compact('requests'));
    }

    public function buyCardRequestStatus(int $id, int $status): RedirectResponse
    {
        return AdminService::saveStatusBuyCard($id, $status);
    }

    public function tradeCardRequest(): Factory|View|Application
    {
        session()->flash('menu-active', 'tradecard-request');
        $dateNow = date('Y-m-d');
        $requests = TradeCard::with('user')->whereTypeTrade('slow')->whereIn('status', [1, 2]);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $requests->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $requests->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $requests->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $requests->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $requests->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $requests = $requests->orderBy('created_at', 'DESC')->get();
        return view('admin.tradecard.request', compact('requests'));
    }

    public function tradeCardRequestSuccess(): Factory|View|Application
    {
        session()->flash('menu-active', 'tradecard-request.success');
        $dateNow = date('Y-m-d');
        $requests = TradeCard::with('user')->whereTypeTrade('slow')->whereStatus(3);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $requests->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $requests->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $requests->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $requests->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $requests->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $requests = $requests->orderBy('created_at', 'DESC')->get();
        return view('admin.tradecard.request', compact('requests'));
    }

    public function tradeCardRequestFail(): Factory|View|Application
    {
        session()->flash('menu-active', 'tradecard-request.fail');
        $dateNow = date('Y-m-d');
        $requests = TradeCard::with('user')->whereTypeTrade('slow')->whereStatus(4);
        if (!empty(request()->username)) {
            $user = User::whereUsername(request()->username)->first();
            $requests->whereUserId($user->id ?? 0);
        }
        if (!empty(request()->filter_from_date)) {
            $requests->where('created_at', '>=', request()->filter_from_date . ' 00:00:00');
        } else {
            $requests->where('created_at', '>=', $dateNow . ' 00:00:00');
        }
        if (!empty(request()->filter_to_date)) {
            $requests->where('created_at', '<=', request()->filter_to_date . ' 23:59:59');
        } else {
            $requests->where('created_at', '<=', $dateNow . ' 23:59:59');
        }
        $requests = $requests->orderBy('created_at', 'DESC')->get();
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
            'api_key_365',
            'system_active',
            'separator_notification',
            'transfer_fee_fix',
            'transfer_fee',
            'transfer_turns_on_day',
            'transfer_money_min',
            'transfer_money_max',
        ]);
        AdminService::saveSystemSetting($params);
        session()->flash('notif', "Thay đổi cài đặt thành công!");
        return back();
    }

    public function viewLogs(): Factory|View|Application
    {
        session()->flash('menu-active', 'trace-log');
        $logs = TraceSystem::logs();
        return view('admin.logs.history', compact('logs'));
    }

    public function notification(): Factory|View|Application
    {
        session()->flash('menu-active', 'notification');
        $notification = Notification::getNotification(true);
        return view('admin.notification.list', compact('notification'));
    }

    public function notificationSave(Request $request): JsonResponse
    {
        if (empty($request->content_new)) {
            return response()->json([
                'success' => false,
                'message' => 'Not see new content to save!'
            ]);
        }
        return AdminService::notificationSave($request->content_new);
    }

    public function notificationChangeStatus(Request $request): JsonResponse
    {
        $alias = $request->alias;
        if (empty($alias)) {
            return response()->json([
                'success' => false,
                'message' => 'Alias của notification không hợp lệ!'
            ]);
        }
        return AdminService::changeStatusNotification($alias);
    }

    public function notificationDelete(Request $request): JsonResponse
    {
        $alias = $request->alias;
        if (empty($alias)) {
            return response()->json([
                'success' => false,
                'message' => 'Alias của notification không hợp lệ!'
            ]);
        }
        return AdminService::deleteNotification($alias);
    }

    public function notificationGetList(): JsonResponse
    {
        $notification = Notification::getNotification(true)->toArray();
        return response()->json([
            'success' => true,
            'datas' => $notification
        ]);
    }

    public function notificationChangeOrder(Request $request): JsonResponse
    {
        $resultOrder = $request->results;
        if (empty($resultOrder)) {
            return response()->json([
                'success' => false,
                'message' => 'Data order notification error!'
            ]);
        }
        return AdminService::changeOrderNotification($resultOrder);
    }

    public function listSystemBank(): Factory|View|Application
    {
        $banks = SystemBank::all();
        session()->flash('menu-active', 'system_bank');
        return view('admin.system_bank.list', compact('banks'));
    }

    public function addSystemBank(SystemBankRequest $request): JsonResponse
    {
        $param = $request->validated();
        $status = ModelService::insert(SystemBank::class, $param) !== false;
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Thành công' : 'Thêm thất bại'
        ]);
    }

    public function deleteSystemBank($id): RedirectResponse
    {
        $bank = SystemBank::whereId($id)->first();
        if ($bank != null) {
            $bank->delete();
        }
        return redirect()->route('admin.system-bank');
    }

    public function getLogsUser(Request $request): JsonResponse
    {
        $activity = UserLogs::getLogs($request->id);
        $tradeCard = TradeCard::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
        $buyCard = CardStore::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
        $recharge = BankMessenger::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
        $withdraw = WithdrawModel::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
        $histories = [
            'activity' => $activity,
            'trade_card' => $tradeCard,
            'buy_card' => $buyCard,
            'recharge' => $recharge,
            'withdraw' => $withdraw
        ];
        $html = view('admin.user.table_log', compact('histories'))->render();
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function getLogsUserWithType(Request $request): JsonResponse
    {
        $userId = $request->id;
        $type = $request->type;
        $page = $request->page;
        if (empty($userId) || empty($type) || empty($page)) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu gửi lên không đầy đủ!'
            ]);
        }
        $html = '';
        switch ($type) {
            case 'activity':
                $logs = UserLogs::getLogs($userId);
                $html = view('admin.user.logs.activity', compact('logs'))->render();
                break;
            case 'trade_card':
                $logs = TradeCard::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
                $html = view('admin.user.logs.trade_card', compact('logs'))->render();
                break;
            case 'buy_card':
                $logs = CardStore::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
                $html = view('admin.user.logs.buy_card', compact('logs'))->render();
                break;
            case 'recharge':
                $logs = BankMessenger::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
                $html = view('admin.user.logs.recharge', compact('logs'))->render();
                break;
            case 'withdraw':
                $logs = WithdrawModel::whereUserId($request->id)->orderBy('created_at', 'DESC')->paginate($this->paginateLog);
                $html = view('admin.user.logs.withdraw', compact('logs'))->render();
                break;
        }

        if (empty($html)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy data!'
            ]);
        }
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function changeLevelUser(Request $request): JsonResponse
    {
        try {
            $newType = $request->newValue;
            $username = $request->username;
            if (!in_array($newType, ['nomal', 'daily', 'tongdaily'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Level không chính xác!'
                ]);
            }
            $user = User::whereUsername($username)->first();
            if ($user == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'User có username là ' . ($request->username ?? 'unknow') . ' không còn tồn tại trên hệ thống!'
                ]);
            }
            $user->type_user = $newType;
            $user->save();
            return response()->json(['success' => true]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi thay đổi level của user ' . ($request->username ?? 'unknow')
            ]);
        }
    }
}
