<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_card', function (Blueprint $table) {
            $table->string('money_user_before')->nullable()->after('money_real');
            $table->string('money_user_after')->nullable()->after('money_user_before');
        });
        Schema::table('card_store', function (Blueprint $table) {
            $table->string('money_user_before')->nullable()->after('money_after_rate');
            $table->string('money_user_after')->nullable()->after('money_user_before');
        });
    }
};
