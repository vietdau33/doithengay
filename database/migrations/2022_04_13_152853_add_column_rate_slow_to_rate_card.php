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
            $table->string('rate_slow')->default('0')->after('rate_use');
        });
        foreach (RateCard::all() as $rate){
            $rate->rate_slow = '10';
            $rate->save();
        }
    }
};
