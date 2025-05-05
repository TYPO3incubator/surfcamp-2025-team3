<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Service;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampBase\Repository\ApiMappingRepository;

class ApiDataMappingService
{
    protected ApiMappingRepository $apiMappingRepository;
    public function __construct(ApiMappingRepository $apiMappingRepository = null){
        $this->apiMappingRepository = $apiMappingRepository ?? GeneralUtility::makeInstance(ApiMappingRepository::class);
    }

    public function mapValues(int $endpointUid, array $data): array
    {
        $endpointMappingConfiguration = $this->apiMappingRepository->findMappingsByEndpointUid($endpointUid);
        $mappedData = [];
        $flattenedData = ArrayUtility::flatten($data);

        foreach($endpointMappingConfiguration as $mappingField) {
            $fieldKey = $mappingField['source'] ?? '';
            $targetKey = $mappingField['target'] ?? '';
            $mappedData[$targetKey] = $flattenedData[$fieldKey];
            settype($mappedData[$targetKey], $mappingField['target_datatype']);
        }

        return $mappedData;

    }
}
