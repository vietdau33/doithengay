<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyCardRequest;
use App\Http\Requests\TradeCardRequest;
use App\Http\Services\CardService;
use App\Http\Services\HttpService;
use App\Http\Services\ModelService;
use App\Http\Services\TradeCardService;
use App\Models\CardStore;
use App\Models\RateCard;
use App\Models\TradeCard;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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
        return view('card.buy');
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

        return redirect()->route('list-card', ['hash' => $hash]);
    }

    public function tradeCard(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-trade-card');
        $rates = RateCard::getRate();
        $rateID = RateCard::getRateId();
        return view('card.trade', compact('rates', 'rateID'));
    }

    /**
     * @throws GuzzleException
     */
    public function tradeCardPost(TradeCardRequest $request)
    {
        if (!CardService::saveTradeCard($request)) {
            return back()->withInput();
        }
        session()->flash('notif', 'Đã gửi yêu cầu! Hãy kiểm tra lịch sử để xem trạng thái gạch thẻ.');
        return redirect()->refresh();
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
