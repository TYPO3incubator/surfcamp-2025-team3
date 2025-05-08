<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http\Client;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use TYPO3\CMS\Core\Domain\RecordInterface;

readonly abstract class AbstractClient
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    protected function checkResponse(ResponseInterface $response): void
    {
        if ($response->getStatusCode() > 300) {
            $errorMessage = 'Unexpected status code: ' . $response->getStatusCode();
            $this->logger->error($errorMessage);
            throw new RuntimeException($errorMessage, code: 1746613028);
        }
    }

    protected function getUrl(RecordInterface $endpoint): string
    {
        $baseUrl = $endpoint->get('base')->get('base_url')->toArray()['url'];
        $path = $endpoint->get('path');

        if (str_starts_with($path, '/')) {
            return $baseUrl . $path;
        }

        return $baseUrl . '/' . $path;

    }
}
