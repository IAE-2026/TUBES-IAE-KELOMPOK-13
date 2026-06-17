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
 
   
    public function getJWKS(): array
    {
        $response = Http::get("{$this->baseUrl}/api/v1/auth/jwks");
 
        if (!$response->successful()) {
            throw new \RuntimeException('Gagal mengambil JWKS dari SSO: ' . $response->status());
        }
 
        return $response->json();
    }
 
   
    public function loginM2M(): string
    {
        $apiKey = env('SSO_M2M_KEY');
 
        $response = Http::post("{$this->baseUrl}/api/v1/auth/token", [
            'api_key' => $apiKey,
        ]);
 
        if (!$response->successful()) {
            throw new \RuntimeException('M2M Login gagal: ' . $response->body());
        }
 
        return $response->json('token');
    }
 
    
    public function loginUser(string $email, string $password): string
    {
        $response = Http::post("{$this->baseUrl}/api/v1/auth/token", [
            'email'    => $email,
            'password' => $password,
        ]);
 
        if (!$response->successful()) {
            throw new \RuntimeException('User login gagal: ' . $response->body());
        }
 
        return $response->json('token');
    }
}