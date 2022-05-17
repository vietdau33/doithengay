<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetCountUserTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-transfer:reset-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset counter time transfer';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        foreach (User::all() as $user) {
            $user->count_number_trasnfer = 0;
            $user->save();
        }
        return 0;
    }
}
