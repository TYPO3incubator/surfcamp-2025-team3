<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http\Client;

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3Incubator\SurfcampBase\Http\ContentTypeHandlers\ResponseHandler;
use TYPO3Incubator\SurfcampBase\Service\CacheService;

readonly class ApiClient extends AbstractClient implements ClientInterface
{
    private const string TYPE = 'JSON/XML';

    public function __construct(
        LoggerInterface $logger,
        private RequestFactory $requestFactory,
        private CacheService $cacheService,
        private ResponseHandler $responseHandler
    ) {
        parent::__construct($logger);
    }

    public function fetch(RecordInterface $endpoint, int $cacheLifetime): array
    {
        $url = $this->getUrl($endpoint);
        $additionalHeaders = $endpoint->get('base')->get('additional_headers');

        $identifier = sha1($url) . sha1(json_encode($additionalHeaders));
        $response = $this->cacheService->get($identifier);
        if ($response === false) {
            $responseObject = $this->requestFactory->request($url, 'GET', ['headers' => $additionalHeaders]);
            $this->checkResponse($responseObject);

            $response = $this->responseHandler->resolveResponseBody($responseObject, $endpoint);

            $this->cacheService->set($identifier, $response, $cacheLifetime);
        }

        return $response;
    }

    public function isResponsible(string $type): bool
    {
        return $type === self::TYPE;
    }
}
