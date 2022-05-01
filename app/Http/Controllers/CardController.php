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
use App\Models\RateCard;
use App\Models\RateCardSell;
use App\Models\TradeCard;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
        $listCard = array_reduce($rates, function($result, $card){
            $card = end($card);
            $result[$card['name']] = [
                'name' => $card['name']
            ];
            return $result;
        }, []);

        return view('card.buy', compact('listCard', 'rates', 'listNotAuto'));
    }

    /**
     * @throws GuzzleException
     */
    public function buyCardPost(BuyCardRequest $request): RedirectResponse
    {
        $status = CardService::buyCardPost($request, $hash);
        if ($status === false) {
            return back()->withInput();
        }

        if($request->type_buy == 'fast') {
            return redirect()->route('list-card', ['hash' => $hash]);
        }
        session()->flash('notif', 'Thẻ đã được đặt mua thành công! Sau 5 phút chưa được xử lý sẽ tự động chuyển sang mua thường!');
        return back();
    }

    public function tradeCard(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-trade-card');
        $listNotAuto = CardListModel::whereAuto('0')->whereType('trade')->get()->toArray();
        $listNotAuto = array_column($listNotAuto, 'name');
        $rates = RateCard::getListCardTrade();
        $cardList = array_reduce($rates, function($result, $card){
            $card = end($card);
            $result[$card['name']] = [
                'name' => $card['name'],
                'rate_id' => $card['rate_id']
            ];
            return $result;
        }, []);
        return view('card.trade', compact('rates', 'cardList', 'listNotAuto'));
    }

    /**
     * @throws GuzzleException
     */
    public function tradeCardPost(TradeCardRequest $request): RedirectResponse
    {
        if(empty($request->type_trade) || !in_array($request->type_trade, ['slow', 'fast'])) {
            session()->flash('mgs_error', "Loại gạch thẻ không chính xác!");
            return back()->withInput();
        }
        $listNotAuto = CardListModel::whereAuto('0')->whereType('trade')->get()->toArray();
        $listNotAuto = array_column($listNotAuto, 'name');
        if(in_array($request->card_type, $listNotAuto)) {
            session()->flash('mgs_error', 'Đổi thẻ nhanh hiện không khả dụng!');
            return back()->withInput();
        }
        if(TradeCard::whereCardSerial($request->card_serial)->orWhere('card_number', $request->card_number)->first() != null) {
            session()->flash('mgs_error', "Số serial hoặc mã thẻ đã tồn tại trên hệ thống!");
            return back()->withInput();
        }
        if ($request->type_trade == 'fast' && !CardService::saveTradeCardFast($request)) {
            return back()->withInput();
        }
        if ($request->type_trade == 'slow' && !CardService::saveTradeCardSlow($request)) {
            return back()->withInput();
        }
        session()->flash('notif', 'Đã gửi yêu cầu! Hãy kiểm tra lịch sử để xem trạng thái gạch thẻ.');
        return back();
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

    public function buyCardHistory(): Factory|View|Application
    {
        $histories = CardStore::whereUserId(user()->id)->orderBy('created_at', 'DESC')->get();
        return view('card.buy_history', compact('histories'));
    }

    public function listCardBuy($hash): RedirectResponse|Factory|View|Application
    {
        $cardStore = CardStore::whereStoreHash($hash)->first();
        if ($cardStore == null) {
            session()->flash('mgs_error', 'Đơn hàng không tồn tại hoặc đã bị xóa! Hãy quay về trang chủ để thao tác lại. Nếu có nhầm lẫn xảy ra, hãy liên hệ admin để được xử lý!');
            return back();
        }
        if($cardStore->user_id != user()->id){
            session()->flash('mgs_error', 'Bạn không phải người mua!');
            return back();
        }
        $lists = json_decode($cardStore->results, 1);
        return view('card.list', compact('lists'));
    }
}
