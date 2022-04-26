<?php

use App\Models\TradeCard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_card', function (Blueprint $table) {
            $table->tinyInteger('status_card')->default(0)->after('hash')->comment('0: Đang kiểm tra, 1: Thẻ đúng, 2: Thẻ sai, 3: Thẻ sai mệnh giá');
            $table->string('money_real')->default(0)->after('status_card');
        });
        foreach (TradeCard::all() as $trade) {
            if(empty($trade->contents)) {
                continue;
            }
            $contents = json_decode($trade->contents, 1);
            $trade->money_real = $contents['real'] ?? 0;

            if($contents['Code'] == 3) {
                $trade->status_card = TradeCard::S_CARD_ERROR;
            }elseif($contents['Code'] == 2) {
                $trade->status_card = $contents['CardSend'] == $contents['CardValue'] ? TradeCard::S_CARD_SUCCESS : TradeCard::S_CARD_HALF;
            }else{
                $trade->status_card = TradeCard::S_CARD_WORKING;
            }

            $trade->save();
        }
    }
};
