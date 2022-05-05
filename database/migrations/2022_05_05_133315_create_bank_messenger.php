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
    public function up(): void
    {
        Schema::create('bank_messenger', function (Blueprint $table){
            $table->id();
            $table->integer('user_id')->nullable();
            $table->double('balance')->nullable();
            $table->double('balance_dau')->nullable();
            $table->double('balance_cuoi')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('bank')->nullable();
            $table->timestamp('time')->nullable();
            $table->string('number')->nullable();
            $table->double('recharge')->nullable();
            $table->string('messenger')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_messenger');
    }
};
