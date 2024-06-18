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
        $user = User::whereHas('fcmTokens')->first();
        if (!$user || $user->fcmTokens()->count() <= 0) {
            return null;
        }

        $apiUrl = config('firebase.api_url');

        $headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json'
        ];

        $fcmTokens = $user->fcmTokens()->pluck('fcm_token')->toArray();

        foreach ($fcmTokens as $fcmToken) {
            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => 'gdfsdg',
                        'body' => 'fasdfas',
                        'image' => 'ffasfsd'
                    ],
                    'data' => [
                        'key' => 'fdsa'
                    ]
                ]
            ];

            $response = Http::withHeaders($headers)->post($apiUrl, $payload);
            FirebaseLogs::create([
                'user_id' => $user->id,
                'data' => json_encode($payload),
                'status' => $response->status()
            ]);
        }
    }



    private function  getAccessToken()  :string
    {
        $credentialsFilePath = config('firebase.firebase_api_key_url');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope(config('firebase.messaging'));
        $client->fetchAccessTokenWithAssertion();
        $token = $client->getAccessToken();

        return $token['access_token'];
    }

}
