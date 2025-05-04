<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\DataProcessors;

use GuzzleHttp\Exception\GuzzleException;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3Incubator\SurfcampBase\Api\ApiClient;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Repository\ApiBaseRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;

class ApiAggregationDataProcessor implements DataProcessorInterface
{
    public function __construct(
        private readonly ApiBaseRepository $apiBaseRepository,
        private readonly ApiEndpointRepository $apiEndpointRepository,
        private readonly ApiClient $apiClient
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws GuzzleException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'apiValues');
        $endpointUid = (int)($cObj->data['endpoint'] ?? 0);

        $responseBody = $this->fetchApiData($endpointUid);
        $processedData[$targetVariableName] = $this->mapValues($responseBody);

        return $processedData;
    }

    /**
     * @throws NotFoundException
     * @throws GuzzleException
     */
    protected function fetchApiData(int $endpointUid): array
    {
        $endpoint = $this->apiEndpointRepository->findByUid($endpointUid);
        $base = $this->apiBaseRepository->findByUid((int)($endpoint['base'] ?? 0));
        return $this->apiClient->get(
            $this->getUrl($base['base_url'] ?? '', $endpoint['path'] ?? ''),
            $base['additional_headers'] ?? []
        );
    }

    protected function getUrl(string $baseUrl, string $endpointUrl): string
    {
        $baseUrl = str_ends_with($baseUrl, '/') ? $baseUrl : $baseUrl . '/';
        $endpointUrl = str_starts_with($endpointUrl, '/') ? substr($endpointUrl, 1) : $endpointUrl;
        return $baseUrl . $endpointUrl;
    }

    protected function mapValues(array $responseBody): array
    {
        return [];
    }
}
