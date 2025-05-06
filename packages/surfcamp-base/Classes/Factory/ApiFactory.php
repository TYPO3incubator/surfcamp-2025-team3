<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Factory;

use TYPO3\CMS\Core\Domain\Record;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Model\Api;

readonly class ApiFactory
{
    /**
     * @throws NotFoundException|\JsonException
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
