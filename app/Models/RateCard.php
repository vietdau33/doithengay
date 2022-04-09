<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateCard extends Model
{
    use HasFactory;

    protected $table = 'rate_card';

    public static function getRate(){
        $rates = array_reduce(self::all()->toArray(), function($result, $rate){
            $result[$rate['name']][] = [
                'price' => $rate['price'],
                'rate' => $rate['rate']
            ];
            return $result;
        }, []);

        foreach ($rates as &$rate) {
            uasort($rate, function($a, $b){
                return (int)$a['price'] > (int)$b['price'];
            });
        }

        return $rates;
    }
}
