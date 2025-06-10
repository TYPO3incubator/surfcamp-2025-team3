<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3Incubator\Plugsy\Exception\NotFoundException;

class ApiMappingRepository
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
                ->from('tx_plugsy_api_fieldmapping')
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
