<?php

namespace TYPO3Incubator\SurfcampBase\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;

class ApiDataMappingService
{
    protected ApiEndpointRepository $apiEndpointRepository;
    public function __construct(ApiEndpointRepository $apiEndpointRepository = null){
        $this->apiEndpointRepository = $apiEndpointRepository ?? GeneralUtility::makeInstance(ApiEndpointRepository::class);
    }

    public function mapValues($endpointUid, $data): array{
        $apiEndpoint = $this->apiEndpointRepository->findByUid($endpointUid);
        $endpointMappingConfiguration = $this->apiEndpointRepository->findMappingsForUid($apiEndpoint);
        $mappedData = [];
        foreach($endpointMappingConfiguration as $mappingField){
            $fieldKey = $mappingField->getSource();
            $mappedData[$fieldKey] = $data[$fieldKey];
            settype($mappedData[$fieldKey],$mappingField->getTargetDatatype());
        }

        return $mappedData;

    }
}
