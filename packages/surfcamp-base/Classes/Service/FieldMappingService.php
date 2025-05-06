<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Service;

use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class FieldMappingService
{
    private const string NESTED_KEY_SEPARATOR = '.';

    public function map(array $data, RecordInterface $endpoint): array
    {
        $flattenedData = ArrayUtility::flatten($data);
        $mappings = $endpoint->get('mappings');

        return $this->processMappings($mappings, $data, $flattenedData);
    }

    private function processMappings(iterable $mappings, array $data, array $flattenedData): array
    {
        $mappedData = [];

        foreach ($mappings as $mapping) {
            $sourceKey = $mapping->get('source') ?? '';
            $targetKey = $mapping->get('target') ?? '';
            $dataType = $mapping->get('target_datatype');

            if (array_key_exists($sourceKey, $flattenedData)) {
                $mappedData[$targetKey] = $this->convertValueType($flattenedData[$sourceKey], $dataType);
            } else {
                $this->processNestedMapping($data, $sourceKey, $targetKey, $dataType, $mappedData);
            }
        }

        return $mappedData;
    }

    private function processNestedMapping(array $data, string $sourceKey, string $targetKey, string $dataType, array &$mappedData): void
    {
        $currentData = $data;
        $keyParts = explode(self::NESTED_KEY_SEPARATOR, $sourceKey);

        foreach ($keyParts as $level => $keyPart) {
            if (array_key_exists($keyPart, $currentData)) {
                $currentData = $currentData[$keyPart];
            } elseif (is_array($currentData)) {
                $this->processArrayItems(
                    $currentData,
                    implode(self::NESTED_KEY_SEPARATOR, array_slice($keyParts, $level)),
                    $targetKey,
                    $dataType,
                    $mappedData
                );
                break;
            }
        }
    }

    private function processArrayItems(
        array $items,
        string $remainingKey,
        string $targetKey,
        string $dataType,
        array &$mappedData
    ): void {
        foreach ($items as $key => $item) {
            $flattenedItem = ArrayUtility::flatten($item);

            if (array_key_exists($remainingKey, $flattenedItem)) {
                $mappedData[$targetKey][$key] = $this->convertValueType($flattenedItem[$remainingKey], $dataType);
            } elseif (array_key_exists($remainingKey, $item)) {
                $mappedData[$targetKey][$key] = $this->convertValueType($item[$remainingKey], $dataType);
            }
        }
    }

    private function convertValueType(mixed $value, string $type): mixed
    {
        settype($value, $type);
        return $value;
    }
}
