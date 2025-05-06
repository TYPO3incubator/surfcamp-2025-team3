<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;

class XmlResponseHandler implements HandlerInterface
{
    private const string CONTENT_TYPE = 'application/xml';

    public function map(ResponseInterface $response, RecordInterface $endpoint): array
    {
        // TODO: Implement map() method.
    }

    public function isResponsible(string $type): bool
    {
        return $type === self::CONTENT_TYPE;
    }
}
