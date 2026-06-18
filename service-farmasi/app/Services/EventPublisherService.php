<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EventPublisherService
{
    protected SoapAuditService $soapAuditService;

    public function __construct(SoapAuditService $soapAuditService)
    {
        $this->soapAuditService = $soapAuditService;
    }

    /**
     * Publish an event to the central RabbitMQ message broker.
     *
     * @param string $routingKey The routing key for the event (e.g. 'prescription.created').
     * @param array $payload The event payload array.
     * @return bool True if published successfully, false otherwise.
     */
    public function publishEvent(string $routingKey, array $payload): bool
    {
        try {
            $token = $this->soapAuditService->getM2mToken();
            if (!$token) {
                Log::error('EventPublisher: Failed to retrieve M2M Token. Aborting publish.');
                return false;
            }

            $url = env('RABBITMQ_PUBLISH_URL', 'https://iae-sso.virtualfri.id/api/v1/messages/publish');
            $exchange = env('RABBITMQ_EXCHANGE', 'iae.central.exchange');

            // Construct the event request body for the SSO RabbitMQ publish gateway
            $requestBody = [
                'exchange' => $exchange,
                'routing_key' => $routingKey,
                'message' => $payload,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post($url, $requestBody);

            if ($response->successful()) {
                Log::info("Event successfully published to exchange [{$exchange}] with key [{$routingKey}]");
                return true;
            }

            Log::error("EventPublisher: Central message broker returned error status [{$response->status()}]: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('EventPublisher: Exception during event publication: ' . $e->getMessage());
            return false;
        }
    }
}
