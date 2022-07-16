<?php

declare(strict_types=1);

namespace App\Transport\Http\Health\Response;

use App\Application\Health\Query\Result\Data\ResourceHealth;
use App\Application\Health\Query\Result\ServiceHealthResult;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="SuccessfulHealthCheckResponse",
 *     type="object",
 *     @OA\Property(
 *         property="isHealthy",
 *         type="boolean",
 *         description="Indicates if the MS and dependencies are healthy or not.",
 *     ),
 *     @OA\Property(
 *         property="resources",
 *         type="array",
 *         description="Lists the health of the external dependencies.",
 *         @OA\Items(
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 example="database",
 *                 description="The name of the external dependency.",
 *             ),
 *             @OA\Property(
 *                 property="isHealthy",
 *                 type="boolean",
 *                 description="Indicates if the external dependency is healthy or not.",
 *             ),
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 description="The error description if there is one being thrown from the external dependency.",
 *                 nullable=true,
 *                 example=null,
 *             ),
 *         ),
 *     ),
 * ),
 * @OA\Schema(
 *     schema="FailingHealthCheckResponse",
 *     type="object",
 *     @OA\Property(
 *         property="isHealthy",
 *         type="boolean",
 *         description="Indicates if the MS and dependencies are healthy or not.",
 *         example=false,
 *     ),
 *     @OA\Property(
 *         property="resources",
 *         type="array",
 *         description="Lists the health of the external dependencies.",
 *         @OA\Items(
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 example="database",
 *                 description="The name of the external dependency.",
 *             ),
 *             @OA\Property(
 *                 property="isHealthy",
 *                 type="boolean",
 *                 description="Indicates if the external dependency is healthy or not.",
 *                 example=false,
 *             )
 *         ),
 *     ),
 * ),
 */
final class HealthCheckResponse implements Responsable
{
    public function __construct(private readonly ServiceHealthResult $serviceHealthResult)
    {
    }

    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: [
                'isHealthy' => $this->serviceHealthResult->isHealthy,
                'resources' => $this->mappedResources(),
            ],
            status: $this->status(),
        );
    }

    /**
     * @return Collection<int|string, array{'name': string, 'isHealthy': bool}>
     */
    private function mappedResources(): Collection
    {
        return collect($this->serviceHealthResult->resources)->map(fn (ResourceHealth $resourceHealth): array => [
            'name' => $resourceHealth->name,
            'isHealthy' => $resourceHealth->isHealthy
        ]);
    }

    private function status(): int
    {
        return $this->serviceHealthResult->isHealthy
            ? Response::HTTP_OK
            : Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
