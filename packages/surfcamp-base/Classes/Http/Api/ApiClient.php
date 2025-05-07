<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use TYPO3\CMS\Core\Http\RequestFactory;

readonly class ApiClient
{
    public function __construct(
        private LoggerInterface $logger,
        private RequestFactory $requestFactory,
    ) {
    }

    public function get(string $url, array $additionalHeaders): ResponseInterface
    {
        $response = $this->requestFactory->request($url, 'GET', ['headers' => $additionalHeaders]);

        if ($response->getStatusCode() < 300) {
            $errorMessage = 'Unexpected status code: ' . $response->getStatusCode();
            $this->logger->error($errorMessage);
            throw new RuntimeException($errorMessage, code: 1746613028);
        }

        return $response;
    }
}
