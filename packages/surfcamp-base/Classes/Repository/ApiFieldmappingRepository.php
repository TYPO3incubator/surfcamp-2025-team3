<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Repository;

use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

#[Autoconfigure(public: true)]
class ApiFieldmappingRepository
{
    public function __construct(
        protected QueryBuilder $queryBuilder
    ) {
    }

    /**
     * @throws Exception
     */
    public function createForEndpoint(int $endpointUid, int $pid, string $topic, array $fields): void
    {
        $queryBuilder = clone $this->queryBuilder;
        $queryBuilder->getRestrictions()->removeAll();

        if (!$topic) {
            $queryBuilder
                ->update('tx_surfcampbase_api_fieldmapping')
                ->set('deleted', 1)
                ->where(
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq(
                            'api_endpoint',
                            $queryBuilder->createNamedParameter($endpointUid)
                        ),
                        $queryBuilder->expr()->neq('parent_field', $queryBuilder->createNamedParameter('none'))
                    )
                )
                ->executeStatement();
        }

        $queryBuilder
            ->update('tx_surfcampbase_api_fieldmapping')
            ->set('deleted', 1)
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq(
                        'api_endpoint',
                        $queryBuilder->createNamedParameter($endpointUid)
                    ),
                    $queryBuilder->expr()->neq('parent_field', $queryBuilder->createNamedParameter($topic)),
                    $queryBuilder->expr()->neq('parent_field', $queryBuilder->createNamedParameter('none')),
                )
            )
            ->executeStatement();

        foreach ($fields as $target => $datatype) {
            $existingRecord = $queryBuilder
                ->select('uid')
                ->from('tx_surfcampbase_api_fieldmapping')
                ->where(
                    $queryBuilder->expr()->eq('api_endpoint', $queryBuilder->createNamedParameter($endpointUid)),
                    $queryBuilder->expr()->eq('target', $queryBuilder->createNamedParameter($target))
                )
                ->executeQuery()
                ->fetchOne();
            if (!$existingRecord) {
                $queryBuilder->insert('tx_surfcampbase_api_fieldmapping')
                    ->values([
                        'pid' => $pid,
                        'crdate' => time(),
                        'tstamp' => time(),
                        'parent_field' => $topic,
                        'api_endpoint' => $endpointUid,
                        'source' => '',
                        'target' => $target,
                        'target_datatype' => $datatype,
                        'is_preset' => 1
                    ])
                    ->executeStatement();
            } else {
                $queryBuilder->update('tx_surfcampbase_api_fieldmapping')
                    ->set('tstamp', time())
                    ->set('deleted', 0)
                    ->where(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($existingRecord)),
                        $queryBuilder->expr()->eq('target', $queryBuilder->createNamedParameter($target))
                    )
                    ->executeStatement();
            }
        }
    }
}
