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
use TYPO3Incubator\SurfcampBase\Service\FieldMappingService;

#[Autoconfigure(public: true)]
readonly class ApiAggregationDataProcessor implements DataProcessorInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private EndPointFactory $endPointFactory,
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

            $cacheLifetime = $this->getCacheLifetime($endpoint, $cObj);
            $responseBody = $this->client->fetch($endpoint, $cacheLifetime);

            $processedData[$targetVariableName] = $this->fieldMappingService->map($responseBody, $endpoint);
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

    protected function getSettings(ContentObjectRenderer $cObj)
    {
        return $cObj->getRequest()->getAttribute('site')->getSettings()->getAll();
    }

    protected function getCacheLifetime(RecordInterface $endpoint, ContentObjectRenderer $cObj): mixed
    {
        $cacheLifetime = $endpoint->get('cache_lifetime');
        if ($cacheLifetime === null || $cacheLifetime === 0) {
            $settings = $this->getSettings($cObj);
            $cacheLifetime = $settings['cache']['response']['lifetime'];
        }
        return $cacheLifetime;
    }
}
