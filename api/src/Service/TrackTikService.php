<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
// use App\Service\TracktikTokenManager;

class TrackTikService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $clientUsername;
    private string $clientPassword;
    private ?string $accessToken = null;
    private ?string $refreshToken = null;

    public function __construct(
        // private TracktikTokenManager $tracktikTokenManager
        private ?LoggerInterface $logger = null
    )
    {
        $this->baseUrl = $_ENV['TRACKTIK_BASE_URL'];
        $this->clientId = $_ENV['TRACKTIK_CLIENT_ID'];
        $this->clientSecret = $_ENV['TRACKTIK_CLIENT_SECRET'];
        $this->clientUsername = $_ENV['TRACKTIK_CLIENT_USERNAME'];
        $this->clientPassword = $_ENV['TRACKTIK_CLIENT_PASSWORD'];

    }

    // deprecated because this method does not work, as i have no username/password. I can only refresh token and use access token.
    private function authenticate(): string
    {
        if ($this->accessToken) return $this->accessToken;


        $client = HttpClient::create();
        $response = $client->request('POST', $this->baseUrl . '/oauth2/access_token', [
            'body' => [
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $this->clientUsername,
                'password' => $this->clientPassword,
            ]
        ]);

        $data = $response->toArray();

        $this->logger->debug('Checking credentials.', [
            'grant_type' => 'password',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->clientUsername,
            'password' => $this->clientPassword,
            'api_url' => $this->baseUrl . '/oauth2/access_token',
            'data_response' => $data,
        ]);

        $this->refreshToken = $data['refresh_token'];
        return $this->accessToken = $data['access_token'];
    }

    public function sendEmployee($employee): array
    {
        $token = $this->authenticate();
        // $token = $this->tracktikTokenManager->getAccessToken();

        $payload = [
            'firstName' => $employee->getFirstName(),
            'lastName' => $employee->getLastName(),
            'email' => $employee->getEmail(),
        ];

        // return [
        //     'token' => $token,
        //     'payload' => $payload,
        // ];

        $client = HttpClient::create();
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
