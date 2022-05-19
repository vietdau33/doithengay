<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_card', function (Blueprint $table) {
            $table->double('rate_use')->default(0)->after('money_real');
        });
    }
};
