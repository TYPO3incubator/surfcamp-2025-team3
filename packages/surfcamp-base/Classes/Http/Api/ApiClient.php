<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http\Api;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

readonly class ApiClient
{
    public function __construct(
        private LoggerInterface $logger,
        private RequestFactory $requestFactory,
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $url, array $additionalHeaders): ResponseInterface
    {
        try {
            return $this->requestFactory->request($url, 'GET', ['headers' => $additionalHeaders]);
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
