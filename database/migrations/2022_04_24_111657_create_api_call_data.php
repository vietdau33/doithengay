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
        Schema::create('api_call_data', function (Blueprint $table) {
            $table->id();
            $table->string('api_key');
            $table->string('telco');
            $table->string('code');
            $table->string('serial');
            $table->string('type');
            $table->string('amount');
            $table->string('request_id');
            $table->string('callback');
            $table->string('hash');
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
        Schema::dropIfExists('api_call_data');
    }
};
