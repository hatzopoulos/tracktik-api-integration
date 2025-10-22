<?php

namespace App\Controller;

// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EmployeeMapperService;
use App\Service\TrackTikService;
// use App\Input\ProviderBEmployeeInput;
use App\Dto\ProviderBEmployeeInput;

class ProviderBController
{
    public function __construct(
        private EmployeeMapperService $mapper,
        private TrackTikService $trackTik
    ) {}

    public function __invoke(ProviderBEmployeeInput $data): JsonResponse
    {
        $employee = $this->mapper->mapFromProviderB($data);
        // $response = $this->trackTik->sendEmployee($employee);
        return new JsonResponse(['status' => 'ok', 'tracktik_response' => $employee]);
    }
}
