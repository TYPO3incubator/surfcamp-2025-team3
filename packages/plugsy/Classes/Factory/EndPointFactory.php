<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Factory;

use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\Plugsy\Exception\NotFoundException;
use TYPO3Incubator\Plugsy\Repository\ApiEndpointRepository;

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
            'tx_plugsy_api_endpoint',
            $this->apiEndpointRepository->findByUid($endpointUid)
        );
    }
}
