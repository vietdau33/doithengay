<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyCardRequest;
use App\Http\Requests\TradeCardRequest;
use App\Http\Services\CardService;
use App\Http\Services\HttpService;
use App\Http\Services\ModelService;
use App\Http\Services\TradeCardService;
use App\Models\CardListModel;
use App\Models\CardStore;
use App\Models\OtpData;
use App\Models\RateCard;
use App\Models\RateCardSell;
use App\Models\TraceSystem;
use App\Models\TradeCard;
use App\Models\UserLogs;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CardController extends Controller
{

    /**
     * @throws GuzzleException
     */
    public function checkRate(): bool
    {
        return CardService::get_rate_card();
    }

    public function buyCard(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-buy-card');

        $listNotAuto = CardListModel::whereAuto('0')->whereType('buy')->get()->toArray();
        $listNotAuto = array_column($listNotAuto, 'name');

        $rates = RateCardSell::getListCardBuy();
        $listCard = array_reduce($rates, function ($result, $card) {
            $card = end($card);
            $result[$card['name']] = [
                'name' => $card['name']
            ];
            return $result;
        }, []);

        $today = Carbon::today()->format('Y-m-d');
        $todayStart = $today . ' 00:00:00';
        $todayEnd = $today . ' 23:59:59';
        $histories = CardStore::whereUserId(user()->id)
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<=', $todayEnd)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('card.buy', compact('listCard', 'rates', 'listNotAuto', 'histories'));
    }

    /**
     * @throws GuzzleException
     */
    public function buyCardPost(BuyCardRequest $request): RedirectResponse
    {
        if (user()->security_level_2 === 1) {
            if (empty($request->otp_hash) || empty($request->otp_code)) {
                session()->flash('mgs_error', 'B???n ch??a nh???p m?? OTP!');
                return back()->withInput();
            }
            if (!OtpData::verify($request->otp_hash, $request->otp_code)) {
                session()->flash('mgs_error', 'M?? OTP kh??ng kh???p!');
                return back()->withInput();
            }
        }
        $status = CardService::buyCardPost($request->validated(), $hash);
        if ($status === false) {
            return back()->withInput();
        }

        TraceSystem::setTrace([
            'mgs' => 'mua th???',
            'username' => user()->username,
            ...$request->validated()
        ]);
        $type = $request->type_buy == 'fast' ? 'Nhanh' : 'Ch???m';
        UserLogs::addLogs(
            "Mua th??? $type. Nh?? m???ng ".ucfirst($request->card_buy).", m???nh gi?? ".number_format($request->money_buy).", s??? l?????ng $request->quantity",
            'buy_card',
            $request->validated()
        );

        if ($request->type_buy == 'fast') {
            return redirect()->route('list-card', ['hash' => $hash]);
        }

        session()->flash('notif', 'Th??? ???? ???????c ?????t mua th??nh c??ng! Sau 2 ph??t ch??a ???????c x??? l?? s??? t??? ?????ng chuy???n sang mua nhanh!');
        return back();
    }

    /**
     * @throws GuzzleException
     */
    public function buyCardMulti(Request $request): JsonResponse
    {
        if (user()->security_level_2 === 1) {
            if (empty($request->otp_hash) || empty($request->otp_code)) {
                return response()->json([
                    'success' => false,
                    'error_buy_card' => false,
                    'errors' => ['B???n ch??a nh???p m?? OTP!'],
                    'errorText' => 'B???n ch??a nh???p m?? OTP!',
                ]);
            }
            if (!OtpData::verify($request->otp_hash, $request->otp_code)) {
                return response()->json([
                    'success' => false,
                    'error_buy_card' => false,
                    'errors' => ['M?? OTP kh??ng kh???p!'],
                    'errorText' => 'M?? OTP kh??ng kh???p!',
                ]);
            }
        }

        $errors = [];
        foreach ($request->datas as $data) {
            if (CardService::buyCardPost($data, $hash) === false) {
                $errors[] = 'Th??? ' . ucfirst($data['card_buy']) . ', m???nh gi?? ' . number_format($data['money_buy']) . ': ' . session()->pull('mgs_error');
            }else{
                $type = $data['type_buy'] == 'fast' ? 'Nhanh' : 'Ch???m';
                UserLogs::addLogs(
                    "Mua th??? $type. Nh?? m???ng ".ucfirst($data['card_buy']).", m???nh gi?? ".number_format($data['money_buy']).", s??? l?????ng {$data['quantity']}",
                    'buy_card',
                    $request->all()
                );
            }
        }

        TraceSystem::setTrace([
            'mgs' => 'mua th???',
            'username' => user()->username,
            ...$request->all()
        ]);

        return response()->json([
            'success' => empty($errors),
            'errors' => $errors,
            'error_buy_card' => true,
            'errorText' => implode('. ', $errors),
        ]);
    }

    public function tradeCard(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-trade-card');
        return view('card.trade');
    }

    /**
     * @throws GuzzleException
     */
    public function tradeCardPost(TradeCardRequest $request): RedirectResponse
    {
        if (empty($request->type_trade) || !in_array($request->type_trade, ['slow', 'fast'])) {
            session()->flash('mgs_error', "Lo???i g???ch th??? kh??ng ch??nh x??c!");
            return back()->withInput();
        }
        $listNotAuto = CardListModel::whereAuto('0')->whereType('trade')->get()->toArray();
        $listNotAuto = array_column($listNotAuto, 'name');
        if (in_array($request->card_type, $listNotAuto) && $request->type_buy == 'fast') {
            session()->flash('mgs_error', '?????i th??? nhanh hi???n kh??ng kh??? d???ng!');
            return back()->withInput();
        }
        if (TradeCard::whereCardNumber($request->card_number)->first() != null) {
            session()->flash('mgs_error', "M?? th??? ???? t???n t???i tr??n h??? th???ng!");
            return back()->withInput();
        }
        if ($request->type_trade == 'fast' && !CardService::saveTradeCardFast($request)) {
            return back()->withInput();
        }
        if ($request->type_trade == 'slow' && !CardService::saveTradeCardSlow($request)) {
            return back()->withInput();
        }
        $type = $request->card_type == 'fast' ? 'Nhanh' : 'Ch???m';
        UserLogs::addLogs(
            "G???ch th??? $type. Nh?? m???ng ".ucfirst($request->card_type).", m???nh gi?? $request->card_money",
            'trade_card',
            $request->all()
        );
        TraceSystem::setTrace([
            'mgs' => '?????i th???',
            'username' => user()->username,
            ...$request->validated()
        ]);
        session()->flash('notif', '???? g???i y??u c???u! H??y ki???m tra l???ch s??? ????? xem tr???ng th??i g???ch th???.');
        return back();
    }

    /**
     * @throws GuzzleException
     */
    public function tradeCardPostAjax(TradeCardRequest $request): JsonResponse
    {
        if (empty($request->type_trade) || !in_array($request->type_trade, ['slow', 'fast'])) {
            return response()->json([
                'success' => false,
                'message' => "Lo???i g???ch th??? kh??ng ch??nh x??c!"
            ]);
        }
        $listNotAuto = CardListModel::whereAuto('0')->whereType('trade')->get()->toArray();
        $listNotAuto = array_column($listNotAuto, 'name');
        if (in_array($request->card_type, $listNotAuto) && $request->type_buy == 'fast') {
            return response()->json([
                'success' => false,
                'message' => "?????i th??? nhanh hi???n kh??ng kh??? d???ng!"
            ]);
        }
        if (TradeCard::whereCardNumber($request->card_number)->first() != null) {
            return response()->json([
                'success' => false,
                'message' => "M?? th??? ???? t???n t???i tr??n h??? th???ng!"
            ]);
        }
        if ($request->type_trade == 'fast' && !CardService::saveTradeCardFast($request)) {
            return response()->json([
                'success' => false,
                'message' => session()->pull('mgs_error')
            ]);
        }
        if ($request->type_trade == 'slow' && !CardService::saveTradeCardSlow($request)) {
            return response()->json([
                'success' => false,
                'message' => session()->pull('mgs_error')
            ]);
        }
        $type = $request->card_type == 'fast' ? 'Nhanh' : 'Ch???m';
        UserLogs::addLogs(
            "G???ch th??? $type. Nh?? m???ng ".ucfirst($request->card_type).", m???nh gi?? $request->card_money",
            'trade_card',
            $request->all()
        );
        TraceSystem::setTrace([
            'mgs' => '?????i th???',
            'username' => user()->username,
            ...$request->validated()
        ]);
        session()->flash('notif', '???? g???i y??u c???u! H??y ki???m tra l???ch s??? ????? xem tr???ng th??i g???ch th???.');
        return response()->json([
            'success' => true,
            'message' => 'Th??nh c??ng!'
        ]);
    }

    public function showDiscount(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-discount');
        $rates = RateCard::getRate();
        return view('card.rate', compact('rates'));
    }

    public function tradeCardHistory(): Factory|View|Application
    {
        $histories = TradeCard::whereUserId(user()->id)->orderBy('created_at', 'DESC')->get();
        $rates = RateCard::getRate();
        $rateID = array_flip(RateCard::getRateId());
        return view('card.trade_history', compact('histories', 'rates', 'rateID'));
    }

    public function tradeCardHistoryFilter(Request $request): JsonResponse
    {
        try{
            $card_type = $request->filter_card_type;
            $money = $request->filter_money;
            $status = $request->filter_status;
            $from_date = $request->filter_from_date;
            $to_date = $request->filter_to_date;

            $histories = TradeCard::whereUserId(user()->id);
            if (!empty($card_type)) {
                $histories->whereCardType($card_type);
            }
            if (!empty($money)) {
                $histories->whereCardMoney($money);
            }
            if (!is_null($status) && $status != '') {
                $histories->whereStatusCard($status);
            }
            if (empty($from_date) && empty($to_date)) {
                $from_date = date('Y-m-d');
                $to_date = date('Y-m-d');
            }
            if (empty($from_date) && !empty($to_date)) {
                $from_date = $to_date;
            }
            if (!empty($from_date) && empty($to_date)) {
                $to_date = $from_date;
            }
            if (strtotime($from_date) > strtotime($to_date)) {
                $a = $from_date;
                $from_date = $to_date;
                $to_date = $a;
            }
            $histories->where('created_at', '>=', $from_date . ' 00:00:00');
            $histories->where('created_at', '<=', $to_date . ' 23:59:59');
            $histories->orderBy('created_at', 'DESC');
            $histories = $histories->get();
            $html = view('card.trade_history_table', compact('histories'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }catch (Exception $exception) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function tradeCardTotalFilter(Request $request) {
        $start = $request->start ?? null;
        $end = $request->end ?? null;
        $cardType = $request->card_type ?? null;
        if($start == null || $end == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data filter kh??ng ch??nh x??c!'
            ]);
        }
        $totals = TradeCard::getTotals($start, $end, $cardType);
        $html = view('card.trade_total_table', compact('totals'))->render();
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function buyCardHistory(): Factory|View|Application
    {
        $histories = CardStore::whereUserId(user()->id)->orderBy('created_at', 'DESC')->get();
        return view('card.buy_history', compact('histories'));
    }

    public function buyCardHistoryFilter(Request $request): JsonResponse
    {
        try{
            $card_buy = $request->filter_card_buy;
            $money_buy = $request->filter_money_buy;
            $from_date = $request->filter_from_date;
            $to_date = $request->filter_to_date;

            $histories = CardStore::whereUserId(user()->id);
            if (!empty($card_buy)) {
                $histories->whereCardBuy($card_buy);
            }
            if (!empty($money_buy)) {
                $histories->whereMoneyBuy($money_buy);
            }
            if (empty($from_date) && empty($to_date)) {
                $from_date = date('Y-m-d');
                $to_date = date('Y-m-d');
            }
            if (empty($from_date) && !empty($to_date)) {
                $from_date = $to_date;
            }
            if (!empty($from_date) && empty($to_date)) {
                $to_date = $from_date;
            }
            if (strtotime($from_date) > strtotime($to_date)) {
                $a = $from_date;
                $from_date = $to_date;
                $to_date = $a;
            }
            $histories->where('created_at', '>=', $from_date . ' 00:00:00');
            $histories->where('created_at', '<=', $to_date . ' 23:59:59');
            $histories->orderBy('created_at', 'DESC');
            $histories = $histories->get();
            $html = view('card.buy_history_table', compact('histories'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }catch (Exception $exception) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function listCardBuy($hash): RedirectResponse|Factory|View|Application
    {
        $cardStore = CardStore::whereStoreHash($hash)->first();
        if ($cardStore == null) {
            session()->flash('mgs_error', '????n h??ng kh??ng t???n t???i ho???c ???? b??? x??a! H??y quay v??? trang ch??? ????? thao t??c l???i. N???u c?? nh???m l???n x???y ra, h??y li??n h??? admin ????? ???????c x??? l??!');
            return back();
        }
        if ($cardStore->user_id != user()->id) {
            session()->flash('mgs_error', 'B???n kh??ng ph???i ng?????i mua!');
            return back();
        }
        $lists = json_decode($cardStore->results, 1);
        return view('card.list', compact('lists'));
    }
}
