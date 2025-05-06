<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Factory;

use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Model\Api;
use TYPO3Incubator\SurfcampBase\Repository\ApiBaseRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiMappingRepository;

readonly class ApiFactory
{
    public function __construct(
        private ApiBaseRepository $baseRepository,
        private ApiEndpointRepository $endpointRepository,
        private ApiMappingRepository $mappingRepository
    ) {
    }

    /**
     * @throws NotFoundException|\JsonException
     */
    public function create(int $baseUid): Api
    {
        $base = $this->baseRepository->findByUid($baseUid);
        $endpoints = $this->endpointRepository->findByBaseUid($baseUid) ?: [];

        $additionalHeaders = json_decode($base['additional_headers'] ?? '{}', true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($additionalHeaders)) {
            $additionalHeaders = [];
        }

        $mappings = [];
        foreach ($endpoints as $endpoint) {
            $mappings[$endpoint['uid']] = $this->mappingRepository->findMappingsByEndpointUid($endpoint['uid']);
        }

        return new Api(
            $base['name'] ?? '',
            $base['base_url'] ?? '',
            $additionalHeaders,
            $endpoints,
            $mappings
        );
    }
}
