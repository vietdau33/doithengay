<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('card_store', function(Blueprint $table) {
            $table->tinyInteger('change_fast')->default(0)->after('status');
        });
        Schema::table('trade_card', function(Blueprint $table) {
            $table->tinyInteger('change_fast')->default(0)->after('status');
        });
    }
};
