<?php

namespace TYPO3Incubator\SurfcampBase\Backend\UserFunctions;
use Doctrine\DBAL\Query\QueryBuilder;
use ScssPhp\ScssPhp\Formatter\Debug;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\HttpFoundation\JsonResponse;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;
use TYPO3Incubator\SurfcampBase\Repository\ApiMappingRepository;

#[Autoconfigure(public: true)]
class SelectAPIResponseKeys
{
    public EndPointFactory $endPointFactory;
    public ApiMappingRepository $apiMappingRepository;
    private $exampleResponse = '{
    "label" => "Label",
    }';

    public function __construct(
        EndPointFactory $endPointFactory,
        ApiMappingRepository $apiMappingRepository
    )
    {
        $this->endPointFactory = $endPointFactory;
        $this->apiMappingRepository = $apiMappingRepository;
    }

    public function selectAPIResponseKeys (&$params): void
    {
        $exampleJson = json_encode(array("Volvo", "BMW", "Toyota"));
        try {
            $mapping = $this->apiMappingRepository->findByUid($params['row']['uid']);
            $endpoint = $this->endPointFactory->create($mapping['api_endpoint']);
            $data = json_decode($endpoint->get('response'));
            $response = [];
            if(is_array($data) && array_key_exists('response', $data) && $data['response'])
                $response = $data['response'];
            $params['items'][] = [''];
        } catch (\Exception $e) {
            $params['items'][] = ['label' => 'item 1 from Exception', 'value' => 'val1'];
        }
    }

}
