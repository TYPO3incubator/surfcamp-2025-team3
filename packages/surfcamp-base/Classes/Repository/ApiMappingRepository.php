<?php

namespace TYPO3Incubator\SurfcampBase\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;

class ApiMappingRepository
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function findMappingsByEndpointUid(int $endpointUid): array
    {
        $queryBuilder = $this->getQueryBuilder();
        try {
            $results =  $queryBuilder->select('*')
                ->from('tx_surfcampbase_api_fieldmapping')
                ->where(
                    $queryBuilder->expr()->eq(
                        'api_endpoint',
                        $queryBuilder->createNamedParameter($endpointUid, ParameterType::INTEGER)
                    ),
                )->executeQuery()
                ->fetchAllAssociative();
            return $results;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new NotFoundException('No mappings found', 1746373658);
        }
    }

    private function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_surfcampbase_api_fieldmapping');
    }
}


