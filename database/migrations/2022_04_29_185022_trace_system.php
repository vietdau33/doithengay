<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trace_system', function(Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('contents');
            $table->timestamps();
        });
    }
};
