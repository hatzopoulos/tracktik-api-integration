<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EmployeeMapperService;
use App\Service\TrackTikService;
use App\Dto\ProviderAEmployeeInput;

class ProviderAController
{
    public function __construct(
        private EmployeeMapperService $mapper,
        private TrackTikService $trackTik
    ) {}

    public function __invoke(ProviderAEmployeeInput $data): JsonResponse
    {
        $employee = $this->mapper->mapFromProviderA($data);
        $response = $this->trackTik->sendEmployee($employee);
        return new JsonResponse(['status' => 'ok', 'tracktik_response' => $response]);
    }
}
