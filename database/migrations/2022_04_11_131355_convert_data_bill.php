<?php

use App\Http\Services\ModelService;
use App\Models\CardListModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('card_list_status', function (Blueprint $table) {
            $table->string('fullname')->nullable()->after('name');
        });
        foreach (CardListModel::whereNull('fullname')->get() as $card) {
            $card->fullname = ucfirst($card->name);
            $card->save();
        }
        foreach (config('bill') as $key => $bill) {
            foreach ($bill['vendor'] as $card => $vendor) {
                $fullname = $vendor['name'] . ' (' . $this->getTextBill($key) . ')';
                ModelService::insert(CardListModel::class, [
                    'name' => "$key|$card",
                    'fullname' => $fullname,
                    'type' => 'bill',
                    'auto' => 0
                ]);
            }
        }
    }

    private function getTextBill($billKey): string
    {
        return match ($billKey) {
            'main_account' => 'TKC',
            'prepaid_mobile' => 'Trả trước',
            'postpaid_mobile' => 'Trả sau',
            'adls' => 'FTTH/ADSL',
            'k_plus' => 'K+',
        };
    }
};
