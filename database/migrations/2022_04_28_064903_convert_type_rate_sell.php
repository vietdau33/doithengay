<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE rate_card_sell MODIFY price double default 0');
        DB::statement('ALTER TABLE rate_card_sell MODIFY rate double default 0');
        DB::statement('ALTER TABLE rate_card_sell MODIFY rate_slow double default 0');
    }
};
