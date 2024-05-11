<?php

namespace App\Services;

use App\Enums\Device;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class PushService
{
    public static function send(array $data, User $user)
    {
        if(!$user->fcmTokens()->count() < 0) {
            return null;
        }

        $credentialsFilePath = public_path('finsoft-ba979-3717459cc9a6.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $token = $client->getAccessToken();
        $access_token = $token['access_token'];


        $apiurl = 'https://fcm.googleapis.com/v1/projects/finsoft-ba979/messages:send';

        $headers = [
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type' => 'application/json'
        ];


        $test_data = [
            "title" => $data['title'],
            "description" => $data['description'],
        ];

        $data = [
            'data' => $test_data,
            'token' => $user->fcmTokens()->where('type', Device::Mobile)->first()
        ];

        $payload = [
            'message' => $data
        ];

        Http::withHeaders($headers)->post($apiurl, $payload);

    }
}
