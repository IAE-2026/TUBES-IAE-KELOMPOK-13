<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Doctor Schedule",
    description: "API Jadwal Dokter"
)]
class DoctorScheduleController extends Controller
{
    #[OA\Get(
        path: "/api/v1/doctor-schedules",
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
            "status" => "success",
            "message" => "Data retrieved successfully",
            "data" => DoctorSchedule::all(),
            "meta" => [
                "service_name" => "JadwalDokter-Service",
                "api_version" => "v1"
            ]
        ]);
    }

    #[OA\Get(
        path: "/api/v1/doctor-schedules/{id}",
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
                "status" => "error",
                "message" => "Data not found",
                "data" => null
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "message" => "Data retrieved successfully",
            "data" => $data
        ]);
    }

    #[OA\Post(
        path: "/api/v1/doctor-schedules",
        summary: "Tambah jadwal dokter",
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
        $data = DoctorSchedule::create($request->all());

        return response()->json([
            "status" => "success",
            "message" => "Data created successfully",
            "data" => $data
        ], 201);
    }
}