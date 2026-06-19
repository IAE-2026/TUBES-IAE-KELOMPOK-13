<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\AuditLog;

class SoapAuditService
{
    private string $url;
    private string $teamId;

    public function __construct()
    {
        $this->url    = env('SSO_BASE_URL', 'https://iae-sso.virtualfri.id') . '/soap/v1/audit';
        $this->teamId = env('SOAP_TEAM_ID', 'TEAM-XX');
    }

    public function sendAudit(string $token, string $activityName, array $logData): array
    {
        $logContent = json_encode($logData);

        $xmlBody = '<?xml version="1.0" encoding="UTF-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central/audit"><soap:Body><iae:AuditRequest><iae:TeamID>' . $this->teamId . '</iae:TeamID><iae:ActivityName>' . $activityName . '</iae:ActivityName><iae:LogContent><![CDATA[' . $logContent . ']]></iae:LogContent></iae:AuditRequest></soap:Body></soap:Envelope>';

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type'  => 'text/xml',
            'SOAPAction'    => '""',
        ])->withBody($xmlBody, 'text/xml')->post($this->url);

        $receiptNumber = null;
        if ($response->successful()) {
            preg_match('/<iae:ReceiptNumber>(.*?)<\/iae:ReceiptNumber>/', $response->body(), $matches);
            $receiptNumber = $matches[1] ?? null;
        }

        AuditLog::create([
            'activity_name'  => $activityName,
            'receipt_number' => $receiptNumber,
            'log_data'       => $logData,
            'success'        => $response->successful(),
        ]);

        return [
            'success'        => $response->successful(),
            'status_code'    => $response->status(),
            'receipt_number' => $receiptNumber,
            'raw_response'   => $response->body(),
        ];
    }
}