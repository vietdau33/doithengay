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
        Schema::create('withdraw', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('bank');
            $table->string('money');
            $table->text('note');
            $table->integer('status')->default(0)->comment('0: Vừa yêu cầu, 1: Đã xác nhận, 2: Thành công, 3: Từ chối');
            $table->text('comment')->nullable()->comment('Lý do từ chối');
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
        Schema::dropIfExists('withdraw');
    }
};
