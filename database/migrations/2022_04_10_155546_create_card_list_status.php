<?php

use App\Http\Services\ModelService;
use App\Models\CardListModel;
use App\Models\RateCard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_list_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->tinyInteger('auto')->default(1);
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });

        foreach (config('card.list') as $name => $card) {
            ModelService::insert(CardListModel::class, [
                'name' => $name,
                'type' => 'buy'
            ]);
        }

        foreach (RateCard::getRate() as $name => $rate) {
            ModelService::insert(CardListModel::class, [
                'name' => $name,
                'type' => 'trade'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_list_status');
    }
};
