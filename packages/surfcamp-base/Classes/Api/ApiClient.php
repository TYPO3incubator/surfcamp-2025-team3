<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class ApiClient
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $url, array $additionalHeaders): array
    {
        $guzzleClient = new Client();

        try {
            $response = $guzzleClient->request('GET', $url, ['headers' => $additionalHeaders]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
