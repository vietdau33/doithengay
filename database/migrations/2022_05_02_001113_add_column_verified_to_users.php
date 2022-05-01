<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('verified')->default(0)->after('role');
            $table->string('hash_verify')->nullable()->after('verified');
        });
        foreach (User::all() as $user) {
            $user->verified = 1;
            $user->save();
        }
    }
};
