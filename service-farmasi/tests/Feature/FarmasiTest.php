<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\Role;
use App\Models\Prescription;
use App\Services\SoapAuditService;
use App\Services\EventPublisherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $roles = ['admin_farmasi', 'apoteker', 'petugas_gudang', 'pasien'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }


    public function test_m2m_token_retrieval()
    {
        $soapAuditService = app(SoapAuditService::class);
        $token = $soapAuditService->getM2mToken();

        $this->assertNotEmpty($token, 'M2M Token should not be empty. Check credentials or SSO server status.');
        $this->assertIsString($token);
    }


    public function test_soap_audit_sending()
    {
        $soapAuditService = app(SoapAuditService::class);
        $logData = [
            'test_key' => 'test_value',
            'timestamp' => now()->toIso8601String()
        ];

        $receiptNumber = $soapAuditService->sendAudit('TestActivity', $logData);

        $this->assertNotEmpty($receiptNumber, 'Receipt number should not be empty.');
        $this->assertTrue(
            str_starts_with($receiptNumber, 'IAE-') || str_starts_with($receiptNumber, 'IAE-FALLBACK-'),
            "Receipt number '$receiptNumber' should match expected format."
        );
    }

   
    public function test_rabbitmq_event_publishing()
    {
        $eventPublisher = app(EventPublisherService::class);
        $payload = [
            'event_id' => uniqid(),
            'message' => 'Integration test message',
            'timestamp' => now()->toIso8601String()
        ];

        $published = $eventPublisher->publishEvent('test.integration.event', $payload);

        $this->assertTrue($published, 'Event should be successfully published to RabbitMQ gateway.');
    }

    
    public function test_e2e_prescription_creation_with_sso()
    {
        $ssoUrl = env('SSO_TOKEN_URL', 'https://iae-sso.virtualfri.id/api/v1/auth/token');
        $email = env('CITIZEN_EMAIL', 'warga25@ktp.iae.id');
        $password = env('CITIZEN_PASSWORD', 'KtpDigital2026!');

        $ssoResponse = Http::post($ssoUrl, [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertTrue($ssoResponse->successful(), 'SSO login for Citizen should be successful.');
        $tokenData = $ssoResponse->json();
        $jwt = $tokenData['access_token'] ?? ($tokenData['token'] ?? null);
        $this->assertNotEmpty($jwt, 'JWT Token must not be empty.');

        $medicine = Medicine::create([
            'nama' => 'Amoxicillin 500mg',
            'kategori' => 'Antibiotik',
            'stock' => 50,
            'harga' => 15000,
            'deskripsi' => 'Obat antibiotik infeksi bakteri.',
            'satuan' => 'Tablet'
        ]);

        $payload = [
            'id_pasien' => 101,
            'id_kunjungan' => 202,
            'nama_dokter' => 'Dr. Bambang Setiadi',
            'items' => [
                [
                    'id_obat' => $medicine->id,
                    'jumlah' => 5,
                    'dosis' => '3x1 setelah makan'
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $jwt,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/prescriptions', $payload);

        $response->assertStatus(201);
        $responseJson = $response->json();

        $this->assertEquals('success', $responseJson['status']);
        $this->assertNotEmpty($responseJson['data']['receipt_number'], 'Receipt number should be populated.');
  
        $medicine->refresh();
        $this->assertEquals(45, $medicine->stock, 'Stock should be reduced by 5.');

        $prescription = Prescription::find($responseJson['data']['id']);
        $this->assertNotNull($prescription);
        $this->assertEquals('pending', $prescription->status);
        $this->assertEquals($responseJson['data']['receipt_number'], $prescription->receipt_number);
    }

    public function test_e2e_prescription_dispensing_with_sso()
    {
        $ssoUrl = env('SSO_TOKEN_URL', 'https://iae-sso.virtualfri.id/api/v1/auth/token');
        $email = env('CITIZEN_EMAIL', 'warga25@ktp.iae.id');
        $password = env('CITIZEN_PASSWORD', 'KtpDigital2026!');

        $ssoResponse = Http::post($ssoUrl, [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertTrue($ssoResponse->successful(), 'SSO login for Citizen should be successful.');
        $tokenData = $ssoResponse->json();
        $jwt = $tokenData['access_token'] ?? ($tokenData['token'] ?? null);
        $this->assertNotEmpty($jwt, 'JWT Token must not be empty.');

        $medicine = Medicine::create([
            'nama' => 'Amoxicillin 500mg',
            'kategori' => 'Antibiotik',
            'stock' => 50,
            'harga' => 15000,
            'deskripsi' => 'Obat antibiotik infeksi bakteri.',
            'satuan' => 'Tablet'
        ]);

        $prescription = Prescription::create([
            'id_pasien' => 101,
            'id_kunjungan' => 202,
            'nama_dokter' => 'Dr. Bambang Setiadi',
            'status' => 'pending',
            'receipt_number' => 'IAE-PRE-TEST-123'
        ]);

        $prescription->items()->create([
            'id_resep' => $prescription->id,
            'id_obat' => $medicine->id,
            'jumlah' => 5,
            'dosis' => '3x1 setelah makan'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $jwt,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/prescriptions/' . $prescription->id);

        $response->assertStatus(200);
        $responseJson = $response->json();

        $this->assertEquals('success', $responseJson['status']);
        $this->assertEquals('dispensed', $responseJson['data']['status'], 'Prescription status should be updated to dispensed.');
        $this->assertNotEmpty($responseJson['data']['receipt_number'], 'Receipt number should be populated.');
        $this->assertNotEquals('IAE-PRE-TEST-123', $responseJson['data']['receipt_number'], 'A new audit receipt number should be obtained.');

        $prescription->refresh();
        $this->assertEquals('dispensed', $prescription->status);
    }
}
