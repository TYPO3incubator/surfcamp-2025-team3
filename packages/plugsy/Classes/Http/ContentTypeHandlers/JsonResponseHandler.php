<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Http\ContentTypeHandlers;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;

class JsonResponseHandler implements HandlerInterface
{
    private const string CONTENT_TYPE = 'application/json';

    public function resolveResponseBody(ResponseInterface $response): array
    {
        return $this->decodeResponseBody($response);
    }

    private function decodeResponseBody(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    public function isResponsible(string $type): bool
    {
        return $type === self::CONTENT_TYPE;
    }
}
