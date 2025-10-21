<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EmployeeMapperService;
use App\Service\TrackTikService;

class ProviderBController
{
    public function __construct(
        private EmployeeMapperService $mapper,
        private TrackTikService $trackTik
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $employee = $this->mapper->mapFromProviderB($data);
        $response = $this->trackTik->sendEmployee($employee);
        return new JsonResponse(['status' => 'ok', 'tracktik_response' => $response]);
    }
}
