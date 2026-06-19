<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Services\SSOService;
use App\Services\SoapAuditService;
use App\Services\AMQPPublisherService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Doctor Schedule",
    description: "API Jadwal Dokter"
)]
class DoctorScheduleController extends Controller
{
    private SSOService $ssoService;
    private SoapAuditService $soapAuditService;
    private AMQPPublisherService $amqpService;

    public function __construct(
        SSOService $ssoService,
        SoapAuditService $soapAuditService,
        AMQPPublisherService $amqpService
    ) {
        $this->ssoService       = $ssoService;
        $this->soapAuditService = $soapAuditService;
        $this->amqpService      = $amqpService;
    }

    #[OA\Get(
        path: "/api/v1/schedules",
        summary: "Ambil semua jadwal dokter",
        tags: ["Doctor Schedule"],
        security: [["ApiKeyAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function index()
    {
        return response()->json([
            "status"  => "success",
            "message" => "Data retrieved successfully",
            "data"    => DoctorSchedule::all(),
            "meta"    => [
                "service_name" => "JadwalDokter-Service",
                "api_version"  => "v1"
            ]
        ]);
    }

    #[OA\Get(
        path: "/api/v1/schedules/{id}",
        summary: "Ambil detail jadwal dokter",
        tags: ["Doctor Schedule"],
        security: [["ApiKeyAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not Found")
        ]
    )]
    public function show($id)
    {
        $data = DoctorSchedule::find($id);

        if (!$data) {
            return response()->json([
                "status"  => "error",
                "message" => "Data not found",
                "data"    => null
            ], 404);
        }

        return response()->json([
            "status"  => "success",
            "message" => "Data retrieved successfully",
            "data"    => $data
        ]);
    }

    #[OA\Post(
        path: "/api/v1/schedules",
        summary: "Booking jadwal dokter",
        tags: ["Doctor Schedule"],
        security: [["ApiKeyAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["doctor_name", "specialization", "schedule_date", "start_time", "end_time"],
                properties: [
                    new OA\Property(property: "doctor_name", type: "string"),
                    new OA\Property(property: "specialization", type: "string"),
                    new OA\Property(property: "schedule_date", type: "string"),
                    new OA\Property(property: "start_time", type: "string"),
                    new OA\Property(property: "end_time", type: "string")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Created")
        ]
    )]
    public function store(Request $request)
    {
        // 1. Login SSO - dapat token
        $token = $this->ssoService->loginM2M();

        // 2. Simpan data booking ke DB
        $data = DoctorSchedule::create(array_merge(
            $request->all(),
            ['status' => 'booked']
        ));

        // 3. Kirim SOAP Audit
        $this->soapAuditService->sendAudit($token, 'BOOKING_DOCTOR_SCHEDULE', [
            'schedule_id'    => $data->id,
            'doctor_name'    => $data->doctor_name,
            'specialization' => $data->specialization,
            'schedule_date'  => $data->schedule_date,
            'start_time'     => $data->start_time,
            'end_time'       => $data->end_time,
            'status'         => $data->status,
        ]);

        // 4. Broadcast event ke RabbitMQ
        $this->amqpService->publish($token, [
            'event'          => 'SCHEDULE_BOOKED',
            'schedule_id'    => $data->id,
            'doctor_name'    => $data->doctor_name,
            'specialization' => $data->specialization,
            'schedule_date'  => $data->schedule_date,
            'status'         => $data->status,
            'timestamp'      => now()->toISOString(),
        ], 'jadwaldokter.booked');

        return response()->json([
            "status"  => "success",
            "message" => "Booking berhasil",
            "data"    => $data
        ], 201);
    }
}