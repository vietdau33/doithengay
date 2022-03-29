<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('card_store', function (Blueprint $table) {
            $table->string('store_hash')->nullable()->after('quantity');
        });
    }
};
