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
        Schema::create('bill', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('type');
            $table->string('vendor_id');
            $table->string('money');
            $table->string('bill_number');
            $table->string('type_pay');
            $table->integer('status')->default(0)->comment('0: Created, 1: Accepted, 2: Success, 3: Cancel');
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
        Schema::dropIfExists('bill');
    }
};
