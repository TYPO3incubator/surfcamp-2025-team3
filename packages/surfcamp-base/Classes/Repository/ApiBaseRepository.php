<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Model\Api;

class ApiBaseRepository
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ApiEndpointRepository $apiEndpointRepository
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function findByUid(int $uid): Api
    {
        $queryBuilder = $this->getQueryBuilder();
        try {
            $record = $queryBuilder->select('*')
                ->from('tx_surfcampbase_api_base')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)
                    )
                )
                ->executeQuery()
                ->fetchAssociative();

            $endpoints = $this->apiEndpointRepository->findByBaseUid($uid);

            return $this->mapRecordToModel($record, $endpoints);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new NotFoundException('Base not found', 1746375124);
        }
    }

    private function mapRecordToModel(array $record, array $endpoints = []): Api
    {
        return new Api(
            $record['name'] ?? '',
            $record['base_url'] ?? '',
            json_decode($record['additional_headers'] ?? '', true),
            $endpoints
        );
    }

    private function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_surfcampbase_api_base');
    }
}
