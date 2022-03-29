<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('card_store', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('quantity')->comment('0: Create, 1: Accept, 2: Success, 3: Cancel');
            $table->text('note')->nullable()->after('status');
        });
    }
};
