<?php

use App\Http\Services\ModelService;
use App\Models\CardListModel;
use App\Models\RateCard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        foreach (RateCard::all() as $rate) {
            $rate->type_rate = 'trade';
            $rate->save();
        }

        $bills = CardListModel::whereType('bill')->get();
        foreach ($bills as $bill) {
            ModelService::insert(RateCard::class, [
                'rate_id' => 0,
                'name' => $bill->name,
                'price' => 0,
                'rate' => 0,
                'rate_use' => 0,
                'type_rate' => 'bill'
            ]);
        }
    }
};
