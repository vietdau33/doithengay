<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY money double default 0');
        DB::statement('ALTER TABLE trade_card MODIFY card_money double default 0');
        DB::statement('ALTER TABLE trade_card MODIFY money_real double default 0');
        DB::statement('ALTER TABLE trade_card MODIFY money_user_before double default 0');
        DB::statement('ALTER TABLE trade_card MODIFY money_user_after double default 0');
        DB::statement('ALTER TABLE card_store MODIFY money_buy double default 0');
        DB::statement('ALTER TABLE card_store MODIFY money_after_rate double default 0');
        DB::statement('ALTER TABLE card_store MODIFY money_user_before double default 0');
        DB::statement('ALTER TABLE card_store MODIFY money_user_after double default 0');
    }
};
