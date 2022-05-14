<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification', function(Blueprint $table) {
            $table->integer('order')->default(0)->after('active');
            $table->dropColumn('expired');
        });
    }
};
