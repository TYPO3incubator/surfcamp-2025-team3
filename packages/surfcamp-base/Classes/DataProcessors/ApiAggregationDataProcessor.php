<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\DataProcessors;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3Incubator\SurfcampBase\Api\ApiClient;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Factory\ApiFactory;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;
use TYPO3Incubator\SurfcampBase\Service\ApiDataMappingService;

#[Autoconfigure(public: true)]
readonly class ApiAggregationDataProcessor implements DataProcessorInterface
{
    public function __construct(
        private ApiEndpointRepository $apiEndpointRepository,
        private ApiClient $apiClient,
        private ApiDataMappingService $apiDataMappingService,
        private ApiFactory $apiFactory,
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws GuzzleException|
     * @throws \JsonException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'apiValues');
        $endpointUid = (int)($cObj->data['api_endpoint'] ?? 0);

        $responseBody = $this->fetchApiData($endpointUid);
        $processedData[$targetVariableName] = $this->apiDataMappingService->mapValues($endpointUid, $responseBody);

        return $processedData;
    }

    /**
     * @throws NotFoundException
     * @throws GuzzleException
     * @throws \JsonException
     */
    protected function fetchApiData(int $endpointUid): array
    {
        $endpoint = $this->apiEndpointRepository->findByUid($endpointUid);
        $base = $this->apiFactory->create($endpoint['base'] ?? 0);
        return $this->apiClient->get(
            $this->getUrl($base->baseUrl ?? '', $endpoint['path'] ?? ''),
            $base->additionalHeaders ?? []
        );
    }

    protected function getUrl(string $baseUrl, string $endpointUrl): string
    {
        $baseUrl = str_ends_with($baseUrl, '/') ? $baseUrl : $baseUrl . '/';
        $endpointUrl = str_starts_with($endpointUrl, '/') ? substr($endpointUrl, 1) : $endpointUrl;
        return $baseUrl . $endpointUrl;
    }
}
