<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_money', function(Blueprint $table){
            $table->id();
            $table->integer('user_id');
            $table->integer('user_receive');
            $table->string('money');
            $table->string('content')->nullable();
            $table->timestamps();
        });
    }
};
