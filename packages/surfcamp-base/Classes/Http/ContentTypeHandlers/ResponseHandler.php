<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http\ContentTypeHandlers;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TYPO3\CMS\Core\Domain\RecordInterface;

readonly class ResponseHandler
{
    public function __construct(private iterable $handlers)
    {
    }

    public function resolveResponseBody(ResponseInterface $response, RecordInterface $endpoint): array
    {
        foreach ($this->handlers as $handler) {
            if ($handler->isResponsible($response->getHeaderLine('Content-Type'))) {
                return $handler->resolveResponseBody($response, $endpoint);
            }
        }

        throw new RuntimeException('No handler found for response', 1746549106);
    }
}
