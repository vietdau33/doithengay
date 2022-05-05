<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('security_level_2')->default(1)->change();
        });
        foreach (User::where('security_level_2', 0)->get() as $user) {
            $user->security_level_2 = 1;
            $user->save();
        }
    }
};
