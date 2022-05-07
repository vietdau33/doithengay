<?php

use App\Models\BillModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bill', function (Blueprint $table) {
            $table->double('money_after_rate')->default(0)->after('money');
        });
        DB::statement('ALTER TABLE bill MODIFY money double default 0');
        foreach (BillModel::all() as $bill){
            $bill->money_after_rate = $bill->money;
            $bill->save();
        }
    }
};
