<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AMQPPublisherService
{
    private string $url      = 'https://iae-sso.virtualfri.id/api/v1/messages/publish';
    private string $exchange = 'iae.central.exchange';

    public function publish(string $token, array $message, ?string $routingKey = null): array
    {
        $payload = [
            'exchange' => $this->exchange,
            'message'  => $message,
        ];

        if ($routingKey) {
            $payload['routing_key'] = $routingKey;
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type'  => 'application/json',
        ])->post($this->url, $payload);

        return [
            'success'     => $response->successful(),
            'status_code' => $response->status(),
            'body'        => $response->json(),
        ];
    }
}