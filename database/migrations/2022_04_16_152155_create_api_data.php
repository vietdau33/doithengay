<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('api_data', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('api_name');
            $table->string('api_key');
            $table->string('api_callback')->nullable();
            $table->integer('api_expire');
            $table->tinyInteger('active')->default(0);
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
        Schema::dropIfExists('api_data');
    }
};
