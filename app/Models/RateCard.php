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
            $result[$rate['name']][] = $rate;
            return $result;
        }, []);

        foreach ($rates as $key => $rate) {
            uasort($rate, function($a, $b){
                return (int)$a['price'] > (int)$b['price'];
            });
            $rates[$key] = [];
            foreach ($rate as $r) {
                $rates[$key][$r['price']] = $r;
            }
        }

        return $rates;
    }

    public static function getRateId(){
        $rate_id = self::select('name', 'rate_id')->groupBy('name')->get()->toArray();
        return array_reduce($rate_id, function($result, $rate){
            $result[$rate['name']] = $rate['rate_id'];
            return $result;
        }, []);
    }
}
