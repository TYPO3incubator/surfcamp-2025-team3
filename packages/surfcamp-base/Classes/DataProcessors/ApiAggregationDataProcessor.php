<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\DataProcessors;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3Incubator\SurfcampBase\Exception\EndpointNotFoundException;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;

class ApiAggregationDataProcessor implements DataProcessorInterface
{
    public function __construct(
        private readonly ApiEndpointRepository $apiEndpointRepository,
    ) {
    }

    /**
     * @throws EndpointNotFoundException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ):void {
        $endpointUid = (int)($cObj->data['endpoint'] ?? 0);
        $endpoint = $this->apiEndpointRepository->findByUid($endpointUid);

        if ($endpoint === null) {
            throw new EndpointNotFoundException('Endpoint not found', 1746373658);
        }
    }
}
