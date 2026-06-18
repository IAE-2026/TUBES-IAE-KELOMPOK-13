<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SoapAuditService
{
    /**
     * Get the Machine-to-Machine (M2M) bearer token from the SSO server.
     */
    public function getM2mToken(): ?string
    {
        try {
            $url = env('SSO_TOKEN_URL', 'https://iae-sso.virtualfri.id/api/v1/auth/token');
            $apiKey = env('SSO_M2M_API_KEY', env('SSO_API_KEY', 'KEY-MHS-176'));

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'api_key' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? ($data['token'] ?? null);
            }

            Log::error('Failed to get M2M Token from SSO: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception during M2M token retrieval: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send audit data to the legacy SOAP Audit Service.
     *
     * @param string $activityName
     * @param array $logData
     * @return string|null The parsed ReceiptNumber or null on failure.
     */
    public function sendAudit(string $activityName, array $logData): ?string
    {
        $token = $this->getM2mToken();
        if (!$token) {
            Log::warning('SOAP Audit: Proceeding with local fallback receipt number due to missing M2M token.');
            return $this->generateFallbackReceipt();
        }

        $teamId = env('IAE_TEAM_ID', env('TEAM_ID', 'TEAM-25'));
        $logContentJson = json_encode($logData);

        // Construct SOAP XML Envelope (CDATA wrapper for JSON payload)
        $xmlPayload = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central/audit">
    <soap:Body>
        <iae:AuditRequest>
            <iae:TeamID>{$teamId}</iae:TeamID>
            <iae:ActivityName>{$activityName}</iae:ActivityName>
            <iae:LogContent><![CDATA[{$logContentJson}]]></iae:LogContent>
        </iae:AuditRequest>
    </soap:Body>
</soap:Envelope>
XML;

        try {
            $url = env('SOAP_AUDIT_URL', 'https://iae-sso.virtualfri.id/soap/v1/audit');

            $response = Http::withHeaders([
                'Content-Type' => 'text/xml; charset=utf-8',
                'Authorization' => 'Bearer ' . $token,
            ])->withBody($xmlPayload, 'text/xml')
              ->post($url);

            if ($response->successful()) {
                $responseBody = $response->body();
                // Parse ReceiptNumber from SOAP XML response using Regex for resilience
                if (preg_match('/<iae:ReceiptNumber>(.*?)<\/iae:ReceiptNumber>/', $responseBody, $matches)) {
                    return $matches[1];
                }
                // Fallback regex in case namespace prefixes vary
                if (preg_match('/ReceiptNumber>(.*?)<\/.*?ReceiptNumber>/', $responseBody, $matches)) {
                    return $matches[1];
                }
                Log::warning('SOAP Audit: Success response received but ReceiptNumber could not be parsed.');
            } else {
                Log::error('SOAP Audit server returned error status ' . $response->status() . ': ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception during SOAP Audit request: ' . $e->getMessage());
        }

        return $this->generateFallbackReceipt();
    }

    /**
     * Generates a unique fallback receipt number if central audit is unavailable.
     */
    private function generateFallbackReceipt(): string
    {
        return 'IAE-FALLBACK-' . strtoupper(bin2hex(random_bytes(4)));
    }
}
