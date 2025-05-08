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
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Http\Client\Client;
use TYPO3Incubator\SurfcampBase\Http\ContentTypeHandlers\ResponseHandler;
use TYPO3Incubator\SurfcampBase\Service\FieldMappingService;

#[Autoconfigure(public: true)]
readonly class ApiAggregationDataProcessor implements DataProcessorInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private EndPointFactory $endPointFactory,
        private ResponseHandler $responseHandler,
        private FieldMappingService $fieldMappingService,
        private Client $client
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
            $endpoint = $this->endPointFactory->create($this->getEndpointUid($cObj));
            $response = $this->client->fetch($endpoint);
            $responseBodyAsArray = $this->responseHandler->resolveResponseBody($response, $endpoint);
            $processedData[$targetVariableName] = $this->fieldMappingService->map($responseBodyAsArray, $endpoint);
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
        }

        return $processedData;
    }

    protected function getEndpointUid(ContentObjectRenderer $cObj): int
    {
        return (int)($cObj->data['api_endpoint'] ?? 0);
    }

    /**
     * @throws GuzzleException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function fetchApiData(RecordInterface $endpoint): ResponseInterface
    {
        return $this->client->fetch($endpoint);
    }
}
