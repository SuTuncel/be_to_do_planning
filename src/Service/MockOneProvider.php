<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class MockOneProvider implements TaskProviderInterface
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getTasks(): array
    {
        try {
            $response = $this->client->request('GET', 'http://127.0.0.1:8000/api/mock-one');
            return $response->toArray();

        } catch (ClientExceptionInterface $e) {
            print_r($e->getMessage());
            return [];
        }
    }
}
