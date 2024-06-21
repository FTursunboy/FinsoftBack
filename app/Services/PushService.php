<?php

namespace App\Services;

use App\Enums\Device;
use App\Http\Resources\Document\DocumentResource;
use App\Models\Document;
use App\Models\FirebaseLogs;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Object_;

class PushService
{
    public function send(User $user, array $data, Model $model, string $type)
    {
        if (!$user || $user->fcmTokens()->count() <= 0) {
            return null;
        }

        $notification = Notification::create([
            'user_id' => $user->id,
            'text' => $data['body'],
            'data' => $data['data'],
            'type' => $type
        ]);

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
                        'title' => $data['title'],
                        'body' => $data['body'],
                        'image' => $data['image'] ?? null
                    ],
                    'data' => [
                        'model' => json_encode($model),
                        'type' => 'document'
                    ]
                ]
            ];

            $response = Http::withHeaders($headers)->post($apiUrl, $payload);
            FirebaseLogs::create([
                'user_id' => $user->id,
                'data' => json_encode($payload),
                'status' => $response->status(),
                'notification_id' => $notification->id,
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
