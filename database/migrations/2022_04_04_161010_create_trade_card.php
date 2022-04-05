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
        Schema::create('trade_card', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('card_type');
            $table->string('card_money');
            $table->string('card_serial');
            $table->string('card_number');
            $table->integer('status')->default(0)->comment('1: Vừa yêu cầu, 2: Đang xử lý, 3: Thành công, 4: Thất bại');
            $table->string('task_id')->nullable();
            $table->text('contents')->nullable();
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
        Schema::dropIfExists('trade_card');
    }
};
