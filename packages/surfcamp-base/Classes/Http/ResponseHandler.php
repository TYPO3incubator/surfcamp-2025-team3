<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Core\Domain\RecordInterface;

readonly class ResponseHandler
{
    public function __construct(private iterable $handlers)
    {
    }

    public function mapResponse(ResponseInterface $response, RecordInterface $endpoint): array
    {
        foreach ($this->handlers as $handler) {
            if ($handler->isResponsible($response->getHeaderLine('Content-Type'))) {
                return $handler->map($response, $endpoint);
            }
        }

        throw new \RuntimeException('No handler found for response');
    }
}
