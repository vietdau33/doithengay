<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function checkRate()
    {
        $client = new Client();

    }

    public function buyCard(): Factory|View|Application
    {
        session()->flash('menu-active', 'menu-buy-card');
        return view('card.buy');
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
