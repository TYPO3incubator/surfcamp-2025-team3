<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Hooks;

use Doctrine\DBAL\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3Incubator\SurfcampBase\Backend\Topic\TopicManager;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Repository\ApiBaseRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiFieldmappingRepository;

#[Autoconfigure(public: true)]
class TtContentDataHandlerHook
{
    public function __construct(
        private readonly TopicManager $topicManager,
        private readonly RecordFactory $recordFactory,
        private readonly ApiBaseRepository $apiBaseRepository,
        private readonly ApiFieldmappingRepository $apiFieldMappingRepository,
    ) {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundException
     */
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        $id,
        array &$fieldArray,
        DataHandler $dataHandler
    ): void {
        if (array_key_exists('topic', $fieldArray)) {
            $base = $this->recordFactory->createResolvedRecordFromDatabaseRow(
                'tx_surfcampbase_api_base',
                $this->apiBaseRepository->findByUid($id)
            );
            $endpoints = $base->get('endpoints');
            $topicConfiguration = $this->topicManager->getTopicConfiguration($fieldArray['topic']);
            $topicFields = $topicConfiguration['fields'] ?? [];
            foreach ($endpoints as $endpoint) {
                $this->apiFieldMappingRepository->createForEndpoint(
                    $endpoint->getUid(),
                    $base->getPid(),
                    $fieldArray['topic'],
                    $topicFields
                );
            }
        }
    }
}
