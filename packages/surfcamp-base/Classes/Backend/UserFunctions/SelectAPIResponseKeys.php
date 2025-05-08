<?php

namespace TYPO3Incubator\SurfcampBase\Backend\UserFunctions;
use Doctrine\DBAL\Query\QueryBuilder;
use ScssPhp\ScssPhp\Formatter\Debug;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\HttpFoundation\JsonResponse;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiMappingRepository;
use TYPO3Incubator\SurfcampBase\Service\FieldMappingService;

#[Autoconfigure(public: true)]
class SelectAPIResponseKeys
{
    public function __construct(
        private EndPointFactory $endPointFactory,
        private ApiMappingRepository $apiMappingRepository
    ) {
    }

    public function selectAPIResponseKeys (&$params): void
    {
        try {
            $mapping = $this->apiMappingRepository->findByUid($params['row']['uid']);
            $endpoint = $this->endPointFactory->create($mapping['api_endpoint']);
            $data = json_decode($endpoint->get('response'), true, 512, JSON_THROW_ON_ERROR);

            if (is_array($data)) {
                $flattenedData = ArrayUtility::flatten($data);
                foreach (array_keys($flattenedData) as $key) {
                    $params['items'][] = ['label' => $key, 'value' => $key];
                }
            }
        } catch (\Exception $e) {
            $params['items'][] = ['label' => 'item 1 from Exception', 'value' => 'val1'];
        }
    }

    private function processNestedMapping(
        array $data,
        string $sourceKey
    ): void {
        $currentData = $data;
        $keyParts = explode(FieldMappingService::NESTED_KEY_SEPARATOR, $sourceKey);

        foreach ($keyParts as $level => $keyPart) {
            if (array_key_exists($keyPart, $data)) {
                $currentData = $currentData[$keyPart];
            } elseif (is_array($currentData)) {
                $this->processArrayItems(
                    $currentData,
                    implode(FieldMappingService::NESTED_KEY_SEPARATOR, array_slice($keyParts, $level)),
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
                $mappedData[$key][$targetKey] = $this->convertValueType($flattenedItem[$remainingKey], $dataType);
            } elseif (array_key_exists($remainingKey, $item)) {
                $mappedData[$key][$targetKey] = $this->convertValueType($item[$remainingKey], $dataType);
            }
        }
    }
}
