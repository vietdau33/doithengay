<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Models\BankModel;
use App\Models\BillModel;
use App\Models\CardListModel;
use App\Models\RateCard;
use App\Models\User;
use App\Models\WithdrawModel;
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

        $withdraw->status = $status;
        $withdraw->save();

        session()->flash('notif', "Thành công!");
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

        $bill->status = $status;
        $bill->save();

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
        $patternMatch = '/^[0-9]{1,2}$/i';
        $patternFloat = '/^[0-9]{1,2}\.[0-9]{1,2}$/i';
        foreach ($params as $key => $rate) {
            $key = explode('rate_', $key);
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

            $r->rate_use = $rate;
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
        if(!in_array($typeChange, ['active', 'auto'], true)) {
            session()->flash('mgs_error', 'Loại trạng thái không chính xác!');
            return back();
        }

        $card = CardListModel::whereName($name)->whereType($typeCard)->first();
        if($card == null) {
            session()->flash('mgs_error', 'Thẻ này không tồn tại trong hệ thống!');
            return back();
        }

        $card->{$typeChange} = (int)!$card->{$typeChange};
        $card->save();

        session()->flash('notif', 'Thay đổi trạng thái thành công!');
        return back();
    }
}
