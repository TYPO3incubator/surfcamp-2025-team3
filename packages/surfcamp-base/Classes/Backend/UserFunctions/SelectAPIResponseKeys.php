<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\UserFunctions;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Throwable;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Repository\ApiMappingRepository;

#[Autoconfigure(public: true)]
readonly class SelectAPIResponseKeys
{
    public function __construct(
        private EndPointFactory $endPointFactory,
        private ApiMappingRepository $apiMappingRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function selectAPIResponseKeys(&$params): void
    {
        try {
            $params['items'] = [];
            $mapping = $this->apiMappingRepository->findByUid($params['row']['uid']);
            $endpoint = $this->endPointFactory->create($mapping['api_endpoint']);
            $data = json_decode($endpoint->get('response'), true, 512, JSON_THROW_ON_ERROR);

            if (is_array($data)) {
                $flattenedData = self::flatten($data);
                foreach (array_keys($flattenedData) as $key) {
                    $params['items'][] = [
                        'label' => (string)$key,
                        'value' => $key
                    ];
                }
            }
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
        }
    }

    private function flatten(array $array, string $prefix = ''): array
    {
        $flatArray = [];

        foreach ($array as $key => $value) {
            $newPrefix = $prefix . '.' . $key;

            if ($prefix === '') {
                $newPrefix = $key;
            }

            if (is_numeric($key) === true) {
                $newPrefix = $prefix;
            }

            $flatArray[$newPrefix] = $value;

            if (is_array($value) === true) {
                $flatArray = array_merge($flatArray, self::flatten($value, $newPrefix));
            }
        }

        return $flatArray;
    }
}
