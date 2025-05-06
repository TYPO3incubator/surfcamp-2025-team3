<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class JsonResponseHandler implements HandlerInterface
{
    private const string CONTENT_TYPE = 'application/json';

    public function map(ResponseInterface $response, RecordInterface $endpoint): array
    {
        $flattenedData = ArrayUtility::flatten(json_decode($response->getBody()->getContents(), true));

        $mappedData = [];
        foreach ($endpoint->get('mappings') as $mapping) {
            $fieldKey = $mapping->get('source') ?? '';
            $targetKey = $mapping->get('target') ?? '';
            $mappedData[$targetKey] = $flattenedData[$fieldKey];
            settype($mappedData[$targetKey], $mapping->get('target_datatype'));
        }

        return $mappedData;
    }

    public function isResponsible(string $type): bool
    {
        return $type === self::CONTENT_TYPE;
    }
}
