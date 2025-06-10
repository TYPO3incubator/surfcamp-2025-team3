<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3Incubator\Plugsy\Model\Api;

readonly class ApiFactory
{
    /**
     * @param Record $base
     * @return Api
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(Record $base): Api
    {
        $additionalHeaders = $base->get('additional_headers');
        if (!is_array($additionalHeaders)) {
            $additionalHeaders = [];
        }

        return new Api(
            $base->get('name') ?? '',
            $base->get('base_url')->toArray()['url'] ?? '',
            $additionalHeaders
        );
    }
}
