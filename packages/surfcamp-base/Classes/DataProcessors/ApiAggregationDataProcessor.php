<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\DataProcessors;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Throwable;
use TYPO3\CMS\Core\Domain\RecordFactory;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3Incubator\SurfcampBase\Exception\NotFoundException;
use TYPO3Incubator\SurfcampBase\Factory\ApiFactory;
use TYPO3Incubator\SurfcampBase\Http\Api\ApiClient;
use TYPO3Incubator\SurfcampBase\Http\ContentTypeHandlers\ResponseHandler;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;
use TYPO3Incubator\SurfcampBase\Service\FieldMappingService;

#[Autoconfigure(public: true)]
readonly class ApiAggregationDataProcessor implements DataProcessorInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private ApiEndpointRepository $apiEndpointRepository,
        private ApiClient $apiClient,
        private ApiFactory $apiFactory,
        private ResponseHandler $responseHandler,
        private RecordFactory $recordFactory,
        private FieldMappingService $fieldMappingService,
    ) {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'apiValues');

        try {
            $endpoint = $this->getEndpoint($this->getEndpointUid($cObj));
            $response = $this->fetchApiData($endpoint);
            $responseBodyAsArray = $this->responseHandler->resolveResponseBody($response, $endpoint);
            $processedData[$targetVariableName] = $this->fieldMappingService->map($responseBodyAsArray, $endpoint);
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
        }

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
     * @throws GuzzleException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function fetchApiData(RecordInterface $endpoint): ResponseInterface
    {
        $base = $this->apiFactory->create($endpoint->get('base') ?? 0);
        return $this->apiClient->get(
            $base->getApiUrl($endpoint->get('path') ?? ''),
            $base->additionalHeaders ?? []
        );
    }

    protected function getEndpointUid(ContentObjectRenderer $cObj): int
    {
        return (int)($cObj->data['api_endpoint'] ?? 0);
    }
}
