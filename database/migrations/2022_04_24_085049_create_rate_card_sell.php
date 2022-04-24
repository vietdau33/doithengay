<?php

use App\Http\Services\ModelService;
use App\Models\RateCard;
use App\Models\RateCardSell;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('rate_card_sell', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->string('rate')->default(0);
            $table->string('rate_slow')->default(0);
            $table->string('type_rate')->default('trade');
            $table->timestamps();
        });
        $this->setDefaultData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_card_sell');
    }

    private function setDefaultData(): void
    {
        foreach (RateCard::whereTypeRate('trade')->get() as $rate) {
            ModelService::insert(RateCardSell::class, [
                'name' => $rate->name,
                'price' => $rate->price
            ]);
        }
    }
};
