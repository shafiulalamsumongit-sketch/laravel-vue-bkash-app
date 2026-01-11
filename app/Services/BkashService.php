<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BkashService
{
    private function headers($token = null)
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => $token,
            'X-APP-Key' => config('bkash.app_key'),
        ];
    }

    public function grantToken()
    {
        $response = Http::withHeaders([
            'username' => config('bkash.username'),
            'password' => config('bkash.password'),
        ])->post(config('bkash.base_url') . '/tokenized/checkout/token/grant', [
            'app_key' => config('bkash.app_key'),
            'app_secret' => config('bkash.app_secret'),
        ]);

        return $response->json();
    }

   
}
