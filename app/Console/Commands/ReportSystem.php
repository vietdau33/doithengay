<?php

namespace App\Console\Commands;

use App\Models\BillModel;
use App\Models\CardStore;
use App\Models\Report;
use App\Models\TradeCard;
use App\Models\WithdrawModel;
use Illuminate\Console\Command;

class ReportSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update report of system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        //Thống kê mua thẻ
        $this->reportBuyCard();
        //Thống kê đổi thẻ nomal
        $this->reportTradeCard();
        //Thống kê đổi thẻ api
        $this->reportTradeCard('api');
        //Thống kê gạch cước
        $this->reportBill();
        //Thống kê rút tiền
        $this->reportWithdraw();
        return 0;
    }

    private function reportBuyCard(): void
    {
        $slow = [
            'all' => 0,
            'success' => 0,
            'error' => 0,
            'pending' => 0,
            'to_fast' => 0,
            'money' => 0,
            'money_after_rate' => 0
        ];
        $fast = [
            'all' => 0,
            'success' => 0,
            'error' => 0,
            'pending' => 0,
            'money' => 0,
            'money_after_rate' => 0
        ];
        $typeSlow = function ($card) use (&$slow) {
            $slow['all']++;
            switch ($card->status) {
                case 0:
                case 1:
                    $slow['pending']++;
                    break;
                case 2:
                    $slow['success']++;
                    break;
                case 3:
                    $slow['error']++;
                    break;
            }
            if ($card->change_fast === 1) {
                $slow['to_fast']++;
            }
            if($card->status === 2){
                $slow['money'] += (int)$card->money_buy;
                $slow['money_after_rate'] += (int)$card->money_after_rate;
            }
        };
        $typeFast = function ($card) use (&$fast) {
            $fast['all']++;
            switch ($card->status) {
                case 0:
                case 1:
                    $fast['pending']++;
                    break;
                case 2:
                    $fast['success']++;
                    break;
                case 3:
                    $fast['error']++;
                    break;
            }
            if($card->status === 2){
                $fast['money'] += (int)$card->money_buy;
                $fast['money_after_rate'] += (int)$card->money_after_rate;
            }
        };
        foreach (CardStore::all() as $card) {
            if ($card->type_buy == 'slow') {
                $typeSlow($card);
            } elseif ($card->type_buy == 'fast') {
                $typeFast($card);
            }
        }
        $setReport = function($report, $type) {
            $report['money'] = number_format($report['money']);
            $report['money_after_rate'] = number_format($report['money_after_rate']);
            foreach ($report as $name => $val){
                Report::setReport('buy_card', "$type.$name", $val);
            }
        };
        $setReport($slow, 'slow');
        $setReport($fast, 'fast');
    }

    private function reportTradeCard($env = 'nomal'): void
    {
        $slow = [
            'all' => 0,
            'success' => 0,
            'error' => 0,
            'error_money' => 0,
            'pending' => 0,
            'to_fast' => 0,
            'money' => 0,
            'money_after_rate' => 0
        ];
        $fast = [
            'all' => 0,
            'success' => 0,
            'error' => 0,
            'error_money' => 0,
            'pending' => 0,
            'money' => 0,
            'money_after_rate' => 0
        ];
        $typeSlow = function ($card) use (&$slow) {
            $slow['all']++;
            switch ($card->status_card) {
                case 0:
                    $slow['pending']++;
                    break;
                case 1:
                    $slow['success']++;
                    break;
                case 2:
                    $slow['error']++;
                    break;
                case 3:
                    $slow['error_money']++;
                    break;
            }
            if ($card->change_fast === 1) {
                $slow['to_fast']++;
            }
            if($card->status_card === 1){
                $slow['money'] += (int)$card->card_money;
                $slow['money_after_rate'] += (int)$card->money_real;
            }
        };
        $typeFast = function ($card) use (&$fast) {
            $fast['all']++;
            switch ($card->status_card) {
                case 0:
                    $fast['pending']++;
                    break;
                case 1:
                    $fast['success']++;
                    break;
                case 2:
                    $fast['error']++;
                    break;
                case 3:
                    $fast['error_money']++;
                    break;
            }
            if($card->status_card === 1){
                $fast['money'] += (int)$card->card_money;
                $fast['money_after_rate'] += (int)$card->money_real;
            }
        };
        foreach (TradeCard::whereTypeCall($env)->get() as $card) {
            if ($card->type_trade == 'slow') {
                $typeSlow($card);
            } elseif ($card->type_trade == 'fast') {
                $typeFast($card);
            }
        }
        $setReport = function($report, $type) use ($env) {
            $report['money'] = number_format($report['money']);
            $report['money_after_rate'] = number_format($report['money_after_rate']);
            foreach ($report as $name => $val){
                Report::setReport('trade_card', "$env.$type.$name", $val);
            }
        };
        $setReport($slow, 'slow');
        $setReport($fast, 'fast');
    }

    private function reportBill(): void
    {
        $bill = [
            'all' => 0,
            'pending' => 0,
            'success' => 0,
            'error' => 0,
            'money' => 0,
            'money_after_rate' => 0
        ];
        foreach (BillModel::all() as $billRequest){
            $bill['all']++;
            $bill['money'] += (int)$billRequest->money;
            $bill['money_after_rate'] += (int)$billRequest->money_after_rate;
            switch ($billRequest->status) {
                case 0:
                case 1:
                    $bill['pending']++;
                    break;
                case 2:
                    $bill['success']++;
                    break;
                case 3:
                    $bill['error']++;
                    break;
            }
        }
        $bill['money'] = number_format($bill['money']);
        $bill['money_after_rate'] = number_format($bill['money_after_rate']);
        foreach ($bill as $key => $val){
            Report::setReport('bill', $key, $val);
        }
    }

    private function reportWithdraw(): void
    {
        $withdraw = [
            'all' => 0,
            'pending' => 0,
            'success' => 0,
            'error' => 0,
            'money' => 0
        ];
        foreach (WithdrawModel::all() as $w){
            $withdraw['all']++;
            if($w->status === 2){
                $withdraw['money'] += (int)$w->money;
            }
            switch ($w->status) {
                case 0:
                case 1:
                    $withdraw['pending']++;
                    break;
                case 2:
                    $withdraw['success']++;
                    break;
                case 3:
                    $withdraw['error']++;
                    break;
            }
        }
        $withdraw['money'] = number_format($withdraw['money']);
        foreach ($withdraw as $key => $val){
            Report::setReport('withdraw', $key, $val);
        }
    }
}
