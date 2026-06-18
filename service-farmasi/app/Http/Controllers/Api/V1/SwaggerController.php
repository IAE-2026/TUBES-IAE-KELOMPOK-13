<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Farmasi Obat API",
    description: "Dokumentasi API Farmasi Obat"
)]
class SwaggerController extends Controller
{
}