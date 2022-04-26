<?php

namespace App\Console\Commands;

use App\Models\ErrorLog;
use Illuminate\Console\Command;

class CheckSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
//        ErrorLog::create([
//            'file' => "No thing",
//            'error_summary' => 'Test Schedule: ' . date('Y-m-d H:i:s'),
//            'log_trace' => strtotime(now())
//        ]);
        return 0;
    }
}
