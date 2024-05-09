<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class ClearInactiveTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-inactive-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear inactive tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        PersonalAccessToken::where('last_used_at', '<', Carbon::now()->subMinute())->delete();
    }
}
