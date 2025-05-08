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
                $flattenedData = self::flatten($data);
                foreach (array_keys($flattenedData) as $key) {
                    $params['items'][] = ['label' => $key, 'value' => $key];
                }
            }
        } catch (\Exception $e) {
            $params['items'][] = ['label' => 'item 1 from Exception', 'value' => 'val1'];
        }
    }

    private function flatten(array $array, string $prefix = '', bool $isChild = false): array
    {
        $flatArray = [];
        foreach ($array as $key => $value) {
            $key = rtrim((string)$key, '.');
            $newPrefix = $prefix . $key . '.';

            if ($isChild === true) {
                $newPrefix = $prefix;
                $isChild = false;
            }

            if (is_array($value) === false) {
                $flatArray[$prefix . $key] = $value;
                continue;
            }

            if (is_array($value) === true && self::hasChildren($value) === true) {
                $isChild = true;
            }

            $flatArray = array_merge($flatArray, self::flatten($value, $newPrefix, $isChild));
        }

        return $flatArray;
    }

    private function hasChildren(array $value): bool
    {
        foreach ($value as $childValue) {
            if (is_array($childValue) === true) {
                return true;
            }
        }

        return false;
    }
}
