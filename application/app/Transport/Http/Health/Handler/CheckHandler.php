<?php

declare(strict_types=1);

namespace App\Transport\Http\Health\Handler;

use App\Application\Health\Query\ServiceHealthQuery;
use App\Transport\Http\Health\Response\HealthCheckResponse;

/**
 * @OA\Get(
 *     path="/api/health",
 *     summary="Provides info on the health of the MS and its dependencies.",
 *     tags={"Health"},
 *     @OA\Response(
 *         response=200,
 *         description="Everything is fine with the MS and its dependencies.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/SuccessfulHealthCheckResponse"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Something is not healthy with the MS or its dependencies.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/FailingHealthCheckResponse"),
 *         ),
 *     ),
 * ),
 */
final class CheckHandler
{
    public function __construct(private readonly ServiceHealthQuery $serviceHealthQuery)
    {
    }

    public function __invoke(): HealthCheckResponse
    {
        return new HealthCheckResponse(serviceHealthResult: $this->serviceHealthQuery->result());
    }
}
