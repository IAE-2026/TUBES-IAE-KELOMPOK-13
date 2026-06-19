<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SSOService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('SSO_BASE_URL', 'https://iae-sso.virtualfri.id');
    }

    public function loginM2M(): string
    {
        $apiKey = env('SSO_M2M_KEY');
        $nim    = env('SSO_M2M_NIM', '102022400220');

        $response = Http::post("{$this->baseUrl}/api/v1/auth/token", [
            'api_key' => $apiKey,
            'nim'     => $nim,
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('M2M Login gagal: ' . $response->body());
        }

        return $response->json('token');
    }
}