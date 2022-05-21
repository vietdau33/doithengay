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
        exec("git status | grep 'modified:'", $outGetFileChange);
        $outGetFileChange = array_map(function($file){
            $file = explode('modified:', $file);
            return trim(end($file));
        }, $outGetFileChange);
        $outGetFileChange = array_filter($outGetFileChange, function($file){
            if($file == 'public/css/custom.css') return false;
            return !str_ends_with($file, '.blade.php');
        });
        if(count($outGetFileChange) == 0) {
            @unlink(public_path('system'));
        }else{
            file_put_contents(public_path('system'), base64_encode(json_encode($outGetFileChange)));
        }
        return 0;
    }
}
