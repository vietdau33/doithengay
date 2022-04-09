<?php

namespace App\Console\Commands;

use App\Http\Services\CardService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class RefreshRateCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rate-card:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Rate Card';

    /**
     * Execute the console command.
     *
     * @return bool
     * @throws GuzzleException
     */
    public function handle(): bool
    {
        return CardService::get_rate_card();
    }
}
