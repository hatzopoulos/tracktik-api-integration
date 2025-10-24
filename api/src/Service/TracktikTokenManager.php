<?php
namespace App\Service;

use App\Entity\TracktikToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TracktikTokenManager
{

    public function __construct(
        private EntityManagerInterface $em,
        private TokenEncryptor $encryptor,
        private HttpClientInterface $client
    ) {
    }

    public function getAccessToken(): string
    {
        // if ($_ENV['TRACKTIK_MOCK'] !== false) {
            // return 'mock-access-token';
        // }

        $repo = $this->em->getRepository(TracktikToken::class);
        $token = $repo->findOneBy([]) ?? new TracktikToken();

        // echo 'test';
        // dd($repo);
        // exit;

        // Bootstrap from .env or secret if DB is empty
        if (!$token->getRefreshToken()) {
            $initial = $_ENV['TRACKTIK_REFRESH_TOKEN'] ?? null;
            if (!$initial) {
                throw new \RuntimeException('No TrackTik refresh token found. Set TRACKTIK_REFRESH_TOKEN in .env.local or secrets.');
            }
            $token->setRefreshToken($this->encryptor->encrypt($initial));
            $this->em->persist($token);
            $this->em->flush();
        }

        // return [
        //     'token' => $token->getAccessToken(),
        //     'payload' => $token->getExpiresAt(),
        // ];

        // print_r($token);exit;

        // Still valid?
        if ($token->getAccessToken() && $token->getExpiresAt() > new \DateTime()) {
            return $this->encryptor->decrypt($token->getAccessToken());
        }

        return $this->refreshAccessToken($token);
    }

    private function refreshAccessToken(TracktikToken $token): string
    {
        $response = $this->client->request('POST', $_ENV['TRACKTIK_BASE_URL'] . '/oauth2/access_token', [
            'body' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->encryptor->decrypt($token->getRefreshToken()),
                'client_id' => $_ENV['TRACKTIK_CLIENT_ID'],
                'client_secret' => $_ENV['TRACKTIK_CLIENT_SECRET'],
            ],
        ]);

        $data = $response->toArray();

        $token->setAccessToken($this->encryptor->encrypt($data['access_token']));
        if (isset($data['refresh_token'])) {
            $token->setRefreshToken($this->encryptor->encrypt($data['refresh_token']));
        }
        $token->setExpiresAt((new \DateTime())->modify('+' . $data['expires_in'] . ' seconds'));

        $this->em->persist($token);
        $this->em->flush();

        return $data['access_token'];
    }
}
