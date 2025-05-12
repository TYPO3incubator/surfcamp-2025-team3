<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Service;

use TYPO3\CMS\Core\Domain\RecordInterface;

class FieldMappingService
{
    private const string NESTED_KEY_SEPARATOR = '.';

    public function map(array $data, RecordInterface $endpoint): array
    {
        $result = [];
        foreach ($endpoint->get('mappings') as $mapping) {
            $sourceKey = $mapping->get('source') ?? '';
            $targetKey = $mapping->get('target') ?? '';
            $parsedData = $this->getValuesByPath($data, $sourceKey[0]);

            $dataType = $mapping->get('target_datatype');

            foreach ($parsedData as $key => $item) {
                $result[$key][$targetKey] = $this->convertValueType($item, $dataType);
            }
        }

        return $result;
    }

    private function getValuesByPath(array $data, string $path): array
    {
        $segments = explode(self::NESTED_KEY_SEPARATOR, $path);
        return $this->searchPath($data, $segments);
    }

    private function searchPath(mixed $current, array $segments): array
    {
        if (empty($segments)) {
            return is_array($current) || is_object($current)
                ? []
                : [$current];
        }

        $key = array_shift($segments);

        if (is_object($current)) {
            if (isset($current->{$key})) {
                return $this->searchPath($current->{$key}, $segments);
            }
            return [];
        }

        if (is_array($current)) {
            $keys = array_keys($current);
            $isList = $keys === range(0, count($current) - 1);

            if (!$isList) {
                if (array_key_exists($key, $current)) {
                    return $this->searchPath($current[$key], $segments);
                }
                return [];
            }

            $results = [];
            foreach ($current as $element) {
                $results = array_merge(
                    $results,
                    $this->searchPath($element, array_merge([$key], $segments))
                );
            }
            return $results;
        }

        return [];
    }

    private function convertValueType(mixed $value, string $type): mixed
    {
        settype($value, $type);
        return $value;
    }
}
