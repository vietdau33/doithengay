<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\SystemSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_setting', function(Blueprint $table) {
            $table->string('feature')->default('')->after('type');
        });

        SystemSetting::setSetting('transfer_fee_fix', '100');
        SystemSetting::setSetting('transfer_fee', '0');
        SystemSetting::setSetting('transfer_turns_on_day', '00');
        SystemSetting::setSetting('transfer_money_min', '10000');
        SystemSetting::setSetting('transfer_money_max', '20000000');

        foreach([
            'api_key_365' => 'api',
            'api_admin_hash' => 'api',
            '____counted_visit____' => 'count_visit',
            'system_active' => 'system_active',
            'key_access_maintenance' => 'key_access_maintenance',
            'separator_notification' => 'notification',
            'transfer_fee_fix' => 'transfer',
            'transfer_fee' => 'transfer',
            'transfer_turns_on_day' => 'transfer',
            'transfer_money_min' => 'transfer',
            'transfer_money_max' => 'transfer',
        ] as $name => $feature) {
            $setting = SystemSetting::whereName($name)->first();
            $setting->feature = $feature;
            $setting->save();
        }
    }
};
