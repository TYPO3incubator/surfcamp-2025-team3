<?php
declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampBase\Repository\ApiMappingRepository;

class ApiDataMappingService
{
    protected ApiMappingRepository $apiMappingRepository;
    public function __construct(ApiMappingRepository $apiMappingRepository = null){
        $this->apiMappingRepository = $apiMappingRepository ?? GeneralUtility::makeInstance(ApiMappingRepository::class);
    }

    public function mapValues($endpointUid, $data): array{
        $endpointMappingConfiguration = $this->apiMappingRepository->findMappingsByEndpointUid($endpointUid);
        $mappedData = [];
        foreach($endpointMappingConfiguration as $mappingField){
            $fieldKey = $mappingField['source'] ?? '';
            $mappedData[$fieldKey] = $data[$fieldKey];
            settype($mappedData[$fieldKey],$mappingField['target_datatype']);
        }

        return $mappedData;

    }
}
