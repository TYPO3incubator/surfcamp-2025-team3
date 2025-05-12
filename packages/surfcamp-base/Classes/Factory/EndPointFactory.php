<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Factory;

use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;

class EndPointFactory
{
    public function __construct(
        private readonly ApiEndpointRepository $apiEndpointRepository,
        private readonly RecordFactory $recordFactory,
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function create(int $endpointUid): RecordInterface
    {
        return $this->recordFactory->createResolvedRecordFromDatabaseRow(
            'tx_surfcampbase_api_endpoint',
            $this->apiEndpointRepository->findByUid($endpointUid)
        );
    }
}
