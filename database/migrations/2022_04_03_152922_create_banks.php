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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('type')->comment('1: Ngân hàng, 2: Ví điện tử');
            $table->string('name')->comment('Tên ngân hàng');
            $table->string('account_number')->comment('Số tài khoản');
            $table->string('account_name')->comment('Tên chủ tài khoản');
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
        Schema::dropIfExists('banks');
    }
};
