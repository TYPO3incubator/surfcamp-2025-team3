<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http\Client;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

readonly class ApiClient extends AbstractClient implements ClientInterface
{
    private const string TYPE = 'JSON/XML';

    public function __construct(
        LoggerInterface $logger,
        private RequestFactory $requestFactory,
    ) {
        parent::__construct($logger);
    }

    public function fetch(RecordInterface $endpoint): ResponseInterface
    {
        $url = $this->getUrl($endpoint);
        $additionalHeaders = $endpoint->get('base')->get('additional_headers');

        $response = $this->requestFactory->request($url, 'GET', ['headers' => $additionalHeaders]);

        $this->checkResponse($response);

        return $response;
    }

    public function isResponsible(string $type): bool
    {
        return $type === self::TYPE;
    }
}
