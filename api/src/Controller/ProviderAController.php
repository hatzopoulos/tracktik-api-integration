<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EmployeeMapperService;
use App\Service\TrackTikService;
use App\Dto\ProviderAEmployeeInput;
use Doctrine\ORM\EntityManagerInterface;

class ProviderAController
{
    public function __construct(
        private EmployeeMapperService $mapper,
        private TrackTikService $trackTik,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(ProviderAEmployeeInput $data): JsonResponse
    {
        // Map incoming DTO to Employee entity
        $employee = $this->mapper->mapFromProviderA($data);

        // Attempt to send to TrackTik first. Only persist locally if that succeeds.
        try {
            $tracktikResponse = $this->trackTik->sendEmployee($employee);
        } catch (\Throwable $e) {
            // don't persist if remote call fails; return an error response
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed sending to TrackTik',
                'error' => $e->getMessage(),
            ], 500);
        }

        if (isset($tracktikResponse['message']) && $tracktikResponse['message'] === 'Unauthorized') {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Unauthorized response from TrackTik',
            ], 401);
        }

        // Persist the mapped Employee locally
        $this->em->persist($employee);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'ok',
            'tracktik_response' => $tracktikResponse,
            'employee_id' => $employee->getId(),
        ]);
    }
}
