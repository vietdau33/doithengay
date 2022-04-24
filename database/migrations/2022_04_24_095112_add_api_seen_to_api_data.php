<?php

use App\Models\ApiData;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_data', function (Blueprint $table) {
            $table->tinyInteger('api_seen')->default(0)->after('api_expire');
        });
        $allApi = ApiData::all();
        $aryAllUserId = array_column($allApi->toArray(), 'user_id');
        foreach (User::all() as $user) {
            if (in_array($user->id, $aryAllUserId)) {
                continue;
            }
            ApiData::createAPI($user->id);
        }
    }
};
