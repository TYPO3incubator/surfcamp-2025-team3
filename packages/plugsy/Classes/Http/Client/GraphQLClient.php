<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Http\Client;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3Incubator\Plugsy\Http\ContentTypeHandlers\ResponseHandler;
use TYPO3Incubator\Plugsy\Service\CacheService;

readonly class GraphQLClient extends AbstractClient implements ClientInterface
{
    private const string TYPE = 'GraphQL';

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
        $body = $endpoint->get('body');

        $identifier = sha1($url) . sha1(json_encode($body));
        $response = $this->cacheService->get($identifier);
        if ($response === false) {
            $responseObject = $this->requestFactory->request($url, 'POST', ['json' => ['query' => $body]]);
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
