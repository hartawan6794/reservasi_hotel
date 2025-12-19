<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Reservasi Hotel API",
 *     version="1.0.0",
 *     description="API documentation for Reservasi Hotel system"
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="Local API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format: (Bearer <token>)"
 * )
 */
class OpenApiSpec
{
}
