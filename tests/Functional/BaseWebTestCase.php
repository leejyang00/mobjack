<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Base test case that provides simple helper functions in order to make HTTP requests and assert on the response.
 */
abstract class BaseWebTestCase extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @param mixed[] $payload
     */
    protected function postRequest(string $uri, array $payload): Response
    {
        return $this->jsonRequest('POST', $uri, $payload);
    }

    /**
     * @param mixed[] $payload
     */
    protected function patchRequest(string $uri, array $payload): Response
    {
        return $this->jsonRequest('PATCH', $uri, $payload);
    }

    /**
     * @return mixed[]
     */
    protected function decodeResponse(Response $response): array
    {
        return (array) \json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param mixed[] $payload
     */
    private function jsonRequest(string $method, string $uri, array $payload): Response
    {
        $this->client->request(
            $method,
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT'  => 'application/json',
            ],
            \json_encode($payload, JSON_THROW_ON_ERROR)
        );

        return $this->client->getResponse();
    }
}