<?php

namespace Database\Seeders;

use App\Enums\AccessMessages;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()
            ->insert([
                [
                'has_access' => 1,
                'next_payment' => Carbon::now()->addDays(3),
                'uuid' => Str::uuid()
                ]
            ]);
    }
}
