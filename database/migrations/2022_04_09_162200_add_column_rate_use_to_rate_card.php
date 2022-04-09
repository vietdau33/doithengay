<?php

use App\Models\RateCard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rate_card', function (Blueprint $table) {
            $table->string('rate_use')->default('')->after('rate');
        });
        foreach (RateCard::all() as $rate){
            $rate->rate_use = $rate->rate;
            $rate->save();
        }
    }
};
