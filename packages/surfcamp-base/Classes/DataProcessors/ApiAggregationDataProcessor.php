<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\DataProcessors;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Factory\ApiFactory;
use TYPO3Incubator\SurfcampBase\Http\Api\ApiClient;
use TYPO3Incubator\SurfcampBase\Http\ResponseHandler;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;

#[Autoconfigure(public: true)]
readonly class ApiAggregationDataProcessor implements DataProcessorInterface
{
    public function __construct(
        private ApiEndpointRepository $apiEndpointRepository,
        private ApiClient $apiClient,
        private ApiFactory $apiFactory,
        private ResponseHandler $responseHandler,
        private RecordFactory $recordFactory,
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
        $endpoint = $this->getEndpoint($this->getEndpointUid($cObj));
        $response = $this->fetchApiData($endpoint);

        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'apiValues');
        $processedData[$targetVariableName] = $this->responseHandler->mapResponse($response, $endpoint);
        return $processedData;
    }

    protected function getEndpoint(int $endpointUid): RecordInterface
    {
        return $this->recordFactory->createResolvedRecordFromDatabaseRow(
            'tx_surfcampbase_api_endpoint',
            $this->apiEndpointRepository->findByUid($endpointUid)
        );
    }

    /**
     * @throws NotFoundException
     * @throws GuzzleException
     * @throws \JsonException
     */
    protected function fetchApiData(RecordInterface $endpoint): ResponseInterface
    {
        $base = $this->apiFactory->create($endpoint->get('base') ?? 0);
        return $this->apiClient->get(
            $this->getUrl($base->baseUrl ?? '', $endpoint->get('path') ?? ''),
            $base->additionalHeaders ?? []
        );
    }

    protected function getUrl(string $baseUrl, string $endpointUrl): string
    {
        $baseUrl = str_ends_with($baseUrl, '/') ? $baseUrl : $baseUrl . '/';
        $endpointUrl = str_starts_with($endpointUrl, '/') ? substr($endpointUrl, 1) : $endpointUrl;
        return $baseUrl . $endpointUrl;
    }

    protected function getEndpointUid(ContentObjectRenderer $cObj): int
    {
        return (int)($cObj->data['api_endpoint'] ?? 0);
    }
}
