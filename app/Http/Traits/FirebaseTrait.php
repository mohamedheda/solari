<?php

namespace App\Http\Traits;


use Exception;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait FirebaseTrait
{
    public function sendNotification($fcms){
        {

//        $credentialsFilePath = base_path('public\json\solari-app-firebase-adminsdk-fbsvc-40bf035281.json');
        $credentialsFilePath = Http::get(asset('solari-app-firebase-adminsdk-fbsvc-40bf035281.json'));
            $client = new GoogleClient();
            try {
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->fetchAccessTokenWithAssertion();
                $token = $client->getAccessToken();
                $access_token = $token['access_token'] ?? null;
            } catch (Exception $e) {
                Log::error('Failed to get access token: ' . $e->getMessage());
            }
            try {
                $headers = [
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json'
                ];

                foreach ($fcms as $fcm) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/solari-app/messages:send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_VERBOSE, false); // Enable verbose output for debugging
                $results = [];
                    $data = [
                        "message" => [
                            "token" => $fcm,
                            "notification" => [
                                "title" => "New Notification",
                                "body" => "Test",
                            ],
                            "data" => (object)[],
                        ],
                    ];
                    $payload = json_encode($data);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                    $response = curl_exec($ch);
                    $err = curl_error($ch);

                    if ($err) {
                        $results[] = [
                            'token' => $fcm,
                            'error' => 'Curl Error: ' . $err
                        ];
                    } else {
                        $results[] = [
                            'token' => $fcm,
                            'response' => json_decode($response, true)
                        ];
                    }
                curl_close($ch);
                }
                Log::error('Notifications have been sent');
            }catch (Exception $e){
                Log::error('Failed because: ' . $e->getMessage());
            }

        }
    }
}
