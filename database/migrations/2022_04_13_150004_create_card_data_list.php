<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_data_list', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('card_type');
            $table->string('card_price');
            $table->string('card_serial');
            $table->string('card_number');
            $table->string('card_expire_time');
            $table->tinyInteger('card_used')->default(0);
            $table->tinyInteger('card_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_data_list');
    }
};
