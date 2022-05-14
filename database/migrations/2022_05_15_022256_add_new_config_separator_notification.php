<?php

use App\Models\SystemSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        SystemSetting::setSetting('separator_notification', '|');
    }
};
