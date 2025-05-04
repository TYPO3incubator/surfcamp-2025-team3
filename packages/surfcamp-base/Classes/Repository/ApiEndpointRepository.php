<?php

declare(strict_types=1);


namespace TYPO3Incubator\SurfcampBase\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ApiEndpointRepository
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function findByUid(int $uid): ?array
    {
        $result = null;
        $queryBuilder = $this->getQueryBuilder();
        try {
            $result = $queryBuilder->select('*')
                ->from('tx_surfcamp_domain_model_apiendpoint')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)
                    ),
                )->executeQuery()
                ->fetchAssociative();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $result;
    }

    private function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_surfcamp_domain_model_apiendpoint');
    }
}
