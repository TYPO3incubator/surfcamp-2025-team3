<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Http\ContentTypeHandlers;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class XmlResponseHandler implements HandlerInterface
{
    private const string CONTENT_TYPE = 'application/xml';

    public function resolveResponseBody(ResponseInterface $response): array
    {
        return $this->parseXmlToArray($response);
    }

    private function parseXmlToArray(ResponseInterface $response): array
    {
        return GeneralUtility::xml2array($response->getBody()->getContents());
    }

    public function isResponsible(string $type): bool
    {
        return str_contains($type, self::CONTENT_TYPE);
    }
}
