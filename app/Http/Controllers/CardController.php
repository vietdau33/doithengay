<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyCardRequest;
use App\Http\Services\CardService;
use App\Http\Services\HttpService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class CardController extends Controller
{
    /**
     * @throws GuzzleException
     */
    public function checkRate()
    {
        $urlCheckRate = CardService::getUrlApi('rate');
        $result = HttpService::ins()->get($urlCheckRate);
        dd($result);
    }

    public function buyCard(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-buy-card');
        return view('card.buy');
    }

    public function buyCardPost(BuyCardRequest $request){
        $status = CardService::buyCardPost($request);
        if($status === false) {
            return back()->withInput();
        }
    }

    public function tradeCard()
    {
        //
    }

    public function checkTradeCard()
    {
        //
    }
}
