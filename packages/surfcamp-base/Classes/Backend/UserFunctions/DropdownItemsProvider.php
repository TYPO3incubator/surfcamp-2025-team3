<?php

namespace TYPO3Incubator\SurfcampBase\Backend\UserFunctions;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiFieldMappingRepository;

#[Autoconfigure(public: true)]
class DropdownItemsProvider {

    public function __construct(
        protected ApiEndpointRepository $apiEndpointRepository,
        protected ApiFieldMappingRepository $apiFieldMappingRepository
    ) {}

    public function getApiEndpoints(&$params): void {
        $apiBaseUid = $params['row']['api_base'][0] ?? 0;
        $params['items'] = [];

        if ($apiBaseUid) {
            $rows = $this->apiEndpointRepository->findAllByBase($apiBaseUid);
            foreach ($rows as $row) {
                $params['items'][] = [$row['name'], $row['uid']];
            }
        }
    }

    public function getApiFieldMappings(&$params): void {
        $apiEndpointUid = $params['row']['api_endpoint'][0] ?? 0;
        $params['items'] = [];

        if ($apiEndpointUid) {
            $rows = $this->apiFieldMappingRepository->findAllByEndpoint($apiEndpointUid);
            foreach ($rows as $row) {
                $params['items'][] = [$row['target'], $row['uid']];
            }
        }
    }
}
