<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('type_user')->default('nomal')->comment('nomal => Thành viên, daily => Đại lý, tongdaily => Tổng đại lý')->after('role');
        });
    }
};
