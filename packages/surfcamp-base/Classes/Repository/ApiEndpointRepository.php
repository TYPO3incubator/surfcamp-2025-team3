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

class ApiEndpointRepository
{
    public function __construct(
        private readonly LoggerInterface $logger,
        protected QueryBuilder $queryBuilder
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function findByUid(int $uid): array|false
    {
        $queryBuilder = clone $this->queryBuilder;
        try {
            return $queryBuilder->select('*')
                ->from('tx_surfcampbase_api_endpoint')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)
                    ),
                )->executeQuery()
                ->fetchAssociative();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new NotFoundException('Endpoint not found', 1746373658);
        }
    }
}
