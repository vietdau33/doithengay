<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateCardSell extends Model
{
    use HasFactory;

    protected $table = 'rate_card_sell';

    public static function getRate($type = 'trade')
    {
        $rates = array_reduce(self::whereTypeRate($type)->get()->toArray(), function ($result, $rate) {
            $result[$rate['name']][] = $rate;
            return $result;
        }, []);

        foreach ($rates as $key => $rate) {
            uasort($rate, function ($a, $b) {
                return (int)$a['price'] > (int)$b['price'];
            });
            $rates[$key] = [];
            foreach ($rate as $r) {
                $rates[$key][$r['price']] = $r;
            }
        }

        return $rates;
    }

    public static function getListCardBuy(): array
    {
        $rates = self::getRate();
        return CardListModel::ingoreInactiveCard($rates, 'buy');
    }
}
