<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rate_card', function(Blueprint $table) {
            $table->double('rate_daily')->default(0)->after('rate_slow');
            $table->double('rate_tongdaily')->default(0)->after('rate_daily');
        });
        Schema::table('rate_card_sell', function(Blueprint $table) {
            $table->double('rate_daily')->default(0)->after('rate_slow');
            $table->double('rate_tongdaily')->default(0)->after('rate_daily');
        });

    }
};
