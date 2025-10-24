<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\EmployeeMapperService;
use App\Service\TrackTikService;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\ProviderBEmployeeInput;

class ProviderBController
{
    public function __construct(
        private EmployeeMapperService $mapper,
        private TrackTikService $trackTik,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private ?LoggerInterface $logger = null
    ) {}

    public function __invoke(Request $request): JsonResponse
    // public function __invoke(ProviderBEmployeeInput $data): JsonResponse
    {

        $raw = $request->getContent();
        try {
            /** @var ProviderAEmployeeInput $data */
            $data = $this->serializer->deserialize($raw, ProviderBEmployeeInput::class, 'json');
        } catch (\Throwable $e) {
            $this->logger?->error('deserialize failed', ['err' => $e->getMessage()]);
            return new JsonResponse(['status' => 'error', 'message' => 'deserialize failed', 'err'=>$e->getMessage()], 400);
        }

        // Map incoming DTO to Employee entity
        $employee = $this->mapper->mapFromProviderB($data);

        // Attempt to send to TrackTik first. Only persist locally if that succeeds.
        try {
            $tracktikResponse = $this->trackTik->sendEmployee($employee);
        } catch (\Throwable $e) {
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
                'tracktik_response' => $tracktikResponse
            ], 401);
        }

        // Persist the mapped Employee locally
        $this->em->persist($employee);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'ok',
            'raw' => $raw,
            'tracktik_response' => $tracktikResponse,
            'employee_id' => $employee->getId(),
            'dto_as_string' => (string)$data,
            'dto_props' => [
                'first' => $data->first,
                'last' => $data->last,
                'email_address' => $data->email_address,
            ],
        ], 200);

    }
}
