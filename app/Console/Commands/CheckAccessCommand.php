<?php

namespace App\Console\Commands;

use App\Enums\Device;
use App\Models\FirebaseLogs;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckAccessCommand extends Command
{
    protected $signature = 'check:access';

    protected $description = 'Command description';

    public function handle()
    {

    }



    private function  getAccessToken()  :string
    {

    }

}
