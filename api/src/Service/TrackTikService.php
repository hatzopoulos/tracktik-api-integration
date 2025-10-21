<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class TrackTikService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private ?string $accessToken = null;
    private ?string $refreshToken = null;

    public function __construct()
    {
        $this->baseUrl = $_ENV['TRACKTIK_BASE_URL'];
        $this->clientId = $_ENV['TRACKTIK_CLIENT_ID'];
        $this->clientSecret = $_ENV['TRACKTIK_CLIENT_SECRET'];
    }

    private function authenticate(): string
    {
        if ($this->accessToken) return $this->accessToken;

        $client = HttpClient::create();
        $response = $client->request('POST', $this->baseUrl . '/oauth2/access_token', [
            'body' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]
        ]);

        $data = $response->toArray();
        $this->refreshToken = $data['refresh_token'];
        return $this->accessToken = $data['access_token'];
    }

    public function sendEmployee($employee): array
    {
        $token = $this->authenticate();
        $client = HttpClient::create();

        $payload = [
            'first_name' => $employee->getFirstName(),
            'last_name' => $employee->getLastName(),
            'email' => $employee->getEmail(),
        ];

        $response = $client->request('POST', $this->baseUrl . '/v1/employees', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);

        return $response->toArray(false);
    }
}
