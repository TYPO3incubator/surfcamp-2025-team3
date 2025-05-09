<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;

class ApiFieldMappingRepository
{
    public function __construct(
        private readonly LoggerInterface $logger,
        protected QueryBuilder $queryBuilder
    ) {
    }

    public function findAllByEndpoint(string $apiEndpoint): array
    {
        $queryBuilder = clone $this->queryBuilder;
        try {
            return  $queryBuilder->select('*')
                ->from('tx_surfcampbase_api_fieldmapping')
                ->where(
                    $queryBuilder->expr()->eq(
                        'api_endpoint',
                        $queryBuilder->createNamedParameter($apiEndpoint, ParameterType::INTEGER)
                    ),
                )->executeQuery()
                ->fetchAllAssociative();

        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new NotFoundException('Endpoint not found', 1746373658);
        }
    }
}
