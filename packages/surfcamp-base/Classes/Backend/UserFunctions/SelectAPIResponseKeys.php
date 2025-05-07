<?php

namespace TYPO3Incubator\SurfcampBase\Backend\UserFunctions;

use Doctrine\DBAL\Query\QueryBuilder;
use ScssPhp\ScssPhp\Formatter\Debug;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiMappingRepository;

#[Autoconfigure(public: true)]
class SelectAPIResponseKeys
{
    public EndPointFactory $endPointFactory;
    public ApiMappingRepository $apiMappingRepository;
    public function __construct(
        EndPointFactory $endPointFactory,
        ApiMappingRepository $apiMappingRepository
    )
    {
        $this->endPointFactory = $endPointFactory;
        $this->apiMappingRepository = $apiMappingRepository;
    }

    public function selectAPIResponseKeys ($data): array
    {
        try {
            $mapping = $this->apiMappingRepository->findByUid($data['row']['uid']);
            $endpoint = $this->endPointFactory->create($mapping['api_endpoint']);
            DebuggerUtility::var_dump($endpoint);
            $response = json_decode($endpoint->get('response'));
            return $data['items'];
        } catch (\Exception) {
            return [];
        }
    }

}
