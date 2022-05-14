<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Models\BankModel;
use App\Models\BillModel;
use App\Models\CardListModel;
use App\Models\CardStore;
use App\Models\Notification;
use App\Models\RateCard;
use App\Models\RateCardSell;
use App\Models\SystemSetting;
use App\Models\TraceSystem;
use App\Models\TradeCard;
use App\Models\User;
use App\Models\WithdrawModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminService extends Service
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public static function saveStatusWithdraw(int $id, int $status): RedirectResponse
    {
        if (!in_array($status, [0, 1, 2, 3], true)) {
            session()->flash('mgs_error', 'Status không chính xác!');
            return redirect()->back();
        }

        $withdraw = WithdrawModel::whereId($id)->first();
        if ($withdraw == null) {
            session()->flash('mgs_error', 'Yêu cầu không tồn tại!');
            return redirect()->back();
        }

        $user = User::whereId($withdraw->user_id)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Người dùng không tồn tại hoặc đã bị xóa!');
            return redirect()->back();
        }

        if ($status == 3) {
            $user->money = (int)$user->money + (int)$withdraw->money;
            $user->save();
        }

        $oldStatus = $withdraw->status;
        $withdraw->status = $status;
        $withdraw->save();

        TraceSystem::setTrace([
            'mgs' => 'Thay đổi trạng thái yêu cầu rút tiền!',
            'withdraw_id' => $withdraw->id,
            'old' => $oldStatus,
            'new' => $status
        ]);

        session()->flash('notif', "Thành công!");
        return redirect()->back();
    }

    public static function saveStatusBuyCard(int $id, int $status): RedirectResponse
    {
        if (!in_array($status, [0, 1, 2, 3], true)) {
            session()->flash('mgs_error', 'Status không chính xác!');
            return redirect()->back();
        }

        $buyCard = CardStore::whereId($id)->first();
        if ($buyCard == null) {
            session()->flash('mgs_error', 'Yêu cầu không tồn tại!');
            return redirect()->back();
        }

        $user = User::whereId($buyCard->user_id)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Người dùng không tồn tại hoặc đã bị xóa!');
            return redirect()->back();
        }

        if ($status == 3) {
            $user->money = (int)$user->money + (int)($buyCard->money_after_rate ?? $buyCard->money);
            $user->save();
        }

        $oldStatus = $buyCard->status;
        $buyCard->status = $status;
        $buyCard->save();

        session()->flash('notif', "Thành công!");
        TraceSystem::setTrace([
            'mgs' => 'Thay đổi trạng thái yêu cầu mua thẻ',
            'buy_card_id' => $buyCard->id,
            'old' => $oldStatus,
            'new' => $status
        ]);
        return redirect()->back();
    }

    public static function saveStatusTradeCard(int $id, int $status): RedirectResponse
    {
        if (!in_array($status, [1, 2, 3, 4], true)) {
            session()->flash('mgs_error', 'Status không chính xác!');
            return redirect()->back();
        }

        $tradeCard = TradeCard::whereId($id)->first();
        if ($tradeCard == null) {
            session()->flash('mgs_error', 'Yêu cầu không tồn tại!');
            return redirect()->back();
        }

        $oldStatus = $tradeCard->status;
        $tradeCard->status = $status;
        $tradeCard->save();

        session()->flash('notif', "Thành công!");
        TraceSystem::setTrace([
            'mgs' => 'Thay đổi trạng thái yêu cầu đổi thẻ',
            'trade_card_id' => $tradeCard->id,
            'old' => $oldStatus,
            'new' => $status
        ]);
        return redirect()->back();
    }

    public static function changeActiveUser(int $id, int $status): RedirectResponse
    {
        if ($status !== 0 && $status !== 1) {
            session()->flash('mgs_error', 'Status không chính xác!');
            return back();
        }

        $user = User::whereId($id)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Người dùng không còn tồn tại!');
            return back();
        }

        $user->inactive = $status;
        $user->save();

        $text = $status == '1' ? 'hủy kích hoạt' : 'kích hoạt';

        TraceSystem::setTrace([
            'mgs' => "Admin $text user!",
            'user_id' => $user->id
        ]);

        session()->flash('notif', "Thay đổi trạng thái thành công!");
        return back();
    }

    public static function changeBillStatus(int $id, int $status): RedirectResponse
    {
        if (!in_array($status, [0, 1, 2, 3], true)) {
            session()->flash('mgs_error', 'Status không chính xác!');
            return redirect()->back();
        }

        $bill = BillModel::whereId($id)->first();
        if ($bill == null) {
            session()->flash('mgs_error', 'Yêu cầu không tồn tại!');
            return redirect()->back();
        }

        $user = User::whereId($bill->user_id)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Người dùng không tồn tại hoặc đã bị xóa!');
            return redirect()->back();
        }

        if ($status == 3) {
            $user->money = (int)$user->money + (int)$bill->money;
            $user->save();
        }

        $oldStatus = $bill->status;
        $bill->status = $status;
        $bill->save();

        TraceSystem::setTrace([
            'mgs' => "Thay đổi trạng thái đơn hàng!",
            'bill_id' => $bill->id,
            'old' => $oldStatus,
            'new' => $status
        ]);

        session()->flash('notif', "Thành công!");
        return redirect()->back();
    }

    public static function getBankInfoAjax($bankId = null): JsonResponse
    {
        if (empty($bankId)) {
            return response()->json([
                'success' => false,
                'message' => "Bank ID empty",
                'datas' => []
            ]);
        }

        $bank = BankModel::whereId($bankId)->first();
        if ($bank == null) {
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

    public static function changeRateCard($name, $params): JsonResponse
    {
        $errors = [];
        $patternMatch = '/^\d{1,2}$/i';
        $patternFloat = '/^\d{1,2}\.\d{1,2}$/i';
        foreach ($params as $key => $rate) {
            $stack = str_starts_with($key, 'rate_') ? 'rate_' : 'slow_';
            $keySave = $stack == 'rate_' ? 'rate_use' : 'rate_slow';

            $key = explode($stack, $key);
            if (count($key) != 2) {
                continue;
            }

            $money = $key[1];
            if (!preg_match($patternMatch, $rate) && !preg_match($patternFloat, $rate)) {
                $errors[] = 'Giá trị chiết khấu không hợp lệ ở mệnh giá: ' . number_format($money);
                continue;
            }

            $r = RateCard::whereName($name)->wherePrice($money)->first();
            if ($r == null) {
                $errors[] = 'Chiết khấu sau không tồn tại: ' . ucfirst($name) . ' mệnh giá ' . number_format($money);
                continue;
            }

            if ($r->{$keySave} != (float)$rate) {
                $oldRate = $r->{$keySave};
                $text = $keySave == 'trace_slow' ? 'chậm' : 'nhanh';
                TraceSystem::setTrace([
                    'mgs' => "Admin thay đổi rate đổi thẻ $text",
                    'rate_trade_id' => $r->id,
                    'card' => $name,
                    'money' => $money,
                    'old' => $oldRate,
                    'new' => $rate
                ]);
            }

            $r->{$keySave} = (float)$rate;
            $r->save();
        }
        return response()->json([
            'success' => empty($errors),
            'message' => empty($errors) ? "Thành công" : "Thay đổi thất bại. Hãy tải lại trang và thử lại!",
            'errors' => $errors
        ]);
    }

    public static function changeRateCardBuy($name, $params): JsonResponse
    {
        $errors = [];
        $patternMatch = '/^\d{1,2}$/i';
        $patternFloat = '/^\d{1,2}\.\d{1,2}$/i';
        foreach ($params as $key => $rate) {
            $stack = str_starts_with($key, 'rate_') ? 'rate_' : 'slow_';
            $keySave = $stack == 'rate_' ? 'rate' : 'rate_slow';

            $key = explode($stack, $key);
            if (count($key) != 2) {
                continue;
            }

            $money = $key[1];
            if (!preg_match($patternMatch, $rate) && !preg_match($patternFloat, $rate)) {
                $errors[] = 'Giá trị chiết khấu không hợp lệ ở mệnh giá: ' . number_format($money);
                continue;
            }

            $r = RateCardSell::whereName($name)->wherePrice($money)->first();
            if ($r == null) {
                $errors[] = 'Chiết khấu sau không tồn tại: ' . ucfirst($name) . ' mệnh giá ' . number_format($money);
                continue;
            }

            if ($r->{$keySave} != (float)$rate) {
                $oldRate = $r->{$keySave};
                $text = $keySave == 'trace_slow' ? 'chậm' : 'nhanh';
                TraceSystem::setTrace([
                    'mgs' => "Admin thay đổi rate mua thẻ $text",
                    'rate_buy_id' => $r->id,
                    'card' => $name,
                    'money' => $money,
                    'old' => $oldRate,
                    'new' => $rate
                ]);
            }

            $r->{$keySave} = (float)$rate;
            $r->save();
        }
        return response()->json([
            'success' => empty($errors),
            'message' => empty($errors) ? "Thành công" : "Thay đổi thất bại. Hãy tải lại trang và thử lại!",
            'errors' => $errors
        ]);
    }

    public static function changeRateBill(array $params): JsonResponse
    {
        $errors = [];
        $configBill = config('bill');
        $patternMatch = '/^[0-9]{1,2}$/i';
        $patternFloat = '/^[0-9]{1,2}\.[0-9]{1,2}$/i';
        foreach ($params as $key => $rate) {
            $typeBill = explode('|', $key);
            $typeFee = $configBill[$typeBill[0]]['text'];
            $typeAccount = get_text_type_account_bill($typeBill[1]);

            if (!preg_match($patternMatch, $rate) && !preg_match($patternFloat, $rate)) {
                $errors[] = "Giá trị chiết khấu không hợp lệ ở loại cước <b>$typeFee</b>, loại tài khoản <b>$typeAccount</b>";
                continue;
            }

            $r = RateCard::whereName($key)->whereTypeRate('bill')->first();
            if ($r == null) {
                $errors[] = "Chiết khấu của loại cước <b>$typeFee</b>, loại tài khoản <b>$typeAccount</b> không tồn tại!";
                continue;
            }

            if ($r->rate_use != (float)$rate) {
                $oldRate = $r->rate_use;
                TraceSystem::setTrace([
                    'mgs' => "Admin thay đổi rate của loại cước $typeFee",
                    'rate_bill_id' => $r->id,
                    'type' => $typeAccount,
                    'old' => $oldRate,
                    'new' => $rate
                ]);
            }

            $r->rate_use = (float)$rate;
            $r->save();
        }
        return response()->json([
            'success' => empty($errors),
            'message' => "Thành công",
            'errors' => $errors
        ]);
    }

    public static function changeStatusCardList($name, $typeChange, $typeCard): RedirectResponse
    {
        if (!in_array($typeChange, ['active', 'auto'], true)) {
            session()->flash('mgs_error', 'Loại trạng thái không chính xác!');
            return back();
        }

        $card = CardListModel::whereName($name)->whereType($typeCard)->first();
        if ($card == null) {
            session()->flash('mgs_error', 'Thẻ này không tồn tại trong hệ thống!');
            return back();
        }

        $typeStatus = match ($typeCard) {
            'buy' => 'bán thẻ',
            'trade' => 'đổi thẻ',
            'bill' => 'gạch cước'
        };

        TraceSystem::setTrace([
            'mgs' => "Thay đổi trạng thái hoạt động của $typeStatus",
            'type' => $typeChange,
            'value' => (int)!$card->{$typeChange},
            'card_id' => $card->id
        ]);

        $card->{$typeChange} = (int)!$card->{$typeChange};
        $card->save();

        session()->flash('notif', 'Thay đổi trạng thái thành công!');
        return back();
    }

    public static function saveSystemSetting(array $params): void
    {
        foreach ($params as $key => $param) {
            SystemSetting::setSetting($key, trim($param));
            TraceSystem::setTrace([
                'mgs' => "Admin thay đổi cài đặt hệ thống!",
                'key' => $key,
                'value' => $param
            ]);
        }
    }

    public static function notificationSave($content): JsonResponse
    {
        $notif = Notification::setNotification($content);
        if (!($notif instanceof Notification)) {
            return response()->json($notif);
        }
        $datas = $notif->toArray();
        $datas['created_at'] = date('d/m/Y', strtotime($datas['created_at']));
        return response()->json([
            'success' => true,
            'message' => 'Thêm thông báo mới thành công!',
            'datas' => $datas
        ]);
    }

    public static function changeStatusNotification(string $alias): JsonResponse
    {
        $notification = Notification::whereAlias($alias)->first();
        if($notification == null) {
            return response()->json([
                'success' => false,
                'message' => 'Notification không tồn tại!'
            ]);
        }
        $notification->active = $notification->active === 1 ? 0 : 1;
        $notification->save();
        return response()->json([
            'success' => true,
            'message' => 'Thành công',
            'active' => $notification->active
        ]);
    }

    public static function deleteNotification(string $alias): JsonResponse
    {
        $notification = Notification::whereAlias($alias)->first();
        if($notification == null) {
            return response()->json([
                'success' => false,
                'message' => 'Notification không tồn tại!'
            ]);
        }
        $notification->delete();
        return response()->json([
            'success' => true,
            'message' => 'Thành công'
        ]);
    }

    public static function changeOrderNotification($aryOrder) {
        try{
            foreach ($aryOrder as $alias => $order) {
                $notif = Notification::whereAlias($alias)->first();
                if($notif == null) continue;
                $notif->order = (int)$order;
                $notif->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Thay đổi order thông báo thành công!'
            ]);
        }catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
