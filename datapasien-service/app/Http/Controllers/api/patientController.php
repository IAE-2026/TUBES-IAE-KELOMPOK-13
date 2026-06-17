<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\SSOService;
use App\Services\SoapAuditService;
use App\Services\AMQPPublisherService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
 
class PatientController extends Controller
{
    public function __construct(
        private SSOService $sso,
        private SoapAuditService $soap,
        private AMQPPublisherService $amqp,
    ) {}
 
    #[OA\Get(
        path: "/api/v1/patients",
        summary: "Ambil semua data pasien",
        security: [["ApiKeyAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function index()
    {
        $patients = Patient::all();
        return response()->json([
            'status'  => 'success',
            'message' => 'Data retrieved successfully',
            'data'    => $patients,
            'meta'    => [
                'service_name' => 'Patient-Service',
                'api_version'  => 'v1'
            ]
        ], 200);
    }
 
    #[OA\Get(
        path: "/api/v1/patients/{id}",
        summary: "Ambil data pasien by ID",
        security: [["ApiKeyAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Patient not found"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function show($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Patient not found',
                'errors'  => null
            ], 404);
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Data retrieved successfully',
            'data'    => $patient,
            'meta'    => [
                'service_name' => 'Patient-Service',
                'api_version'  => 'v1'
            ]
        ], 200);
    }
 
    #[OA\Post(
        path: "/api/v1/patients",
        summary: "Registrasi pasien baru",
        security: [["ApiKeyAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "nik", "phone", "birth_date", "address"],
                properties: [
                    new OA\Property(property: "name",       type: "string", example: "Widia Mesra"),
                    new OA\Property(property: "nik",        type: "string", example: "102022430029"),
                    new OA\Property(property: "phone",      type: "string", example: "082276162672"),
                    new OA\Property(property: "birth_date", type: "string", example: "2007-01-13"),
                    new OA\Property(property: "address",    type: "string", example: "Medan"),
                    new OA\Property(property: "allergies",  type: "string", example: "Buah")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Patient created"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'nik'        => 'required|string|size:16|unique:patients,nik',
            'phone'      => 'required|string|max:20',
            'birth_date' => 'required|date',
            'address'    => 'required|string',
            'allergies'  => 'nullable|string',
        ]);
 
      
        $patient = Patient::create($validated);
 
        
        $receiptNumber = null;
        $amqpResult    = null;
 
        try {
            $m2mToken = $this->sso->loginM2M();
 
            
            $soapResult    = $this->soap->sendAudit(
                token:        $m2mToken,
                activityName: 'PatientRegistered',
                logData:      [
                    'patient_id' => $patient->id,
                    'name'       => $patient->name,
                    'nik'        => $patient->nik,
                    'registered_at' => now()->toISOString(),
                ]
            );
            $receiptNumber = $soapResult['receipt_number'];
 
            
            $amqpResult = $this->amqp->publish(
                token:      $m2mToken,
                message:    [
                    'event'      => 'PatientRegistered',
                    'patient_id' => $patient->id,
                    'name'       => $patient->name,
                    'nik'        => $patient->nik,
                    'timestamp'  => now()->toISOString(),
                ],
                routingKey: 'patient.registered'
            );
 
        } catch (\Exception $e) {
            \Log::error('SOAP/AMQP error on PatientRegistered: ' . $e->getMessage());
        }
 
        return response()->json([
            'status'  => 'success',
            'message' => 'Patient registered successfully',
            'data'    => $patient,
            'meta'    => [
                'service_name'   => 'Patient-Service',
                'api_version'    => 'v1',
                'audit_receipt'  => $receiptNumber,  
                'amqp_published' => $amqpResult['success'] ?? false,
                'amqp_response' => $amqpResult['body'] ?? null
            ]
        ], 201);
    }
}
 