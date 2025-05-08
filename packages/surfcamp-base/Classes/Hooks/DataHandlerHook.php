<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Hooks;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Throwable;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Http\Client\ApiClient;
use TYPO3Incubator\SurfcampBase\Http\ContentTypeHandlers\ResponseHandler;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;

#[Autoconfigure(public: true)]
class DataHandlerHook
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EndPointFactory $endPointFactory,
        private readonly ApiClient $apiClient,
        private readonly ApiEndpointRepository $apiEndpointRepository,
        private readonly ResponseHandler $responseHandler,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string &$table,
        string &$id,
        array &$fieldArray,
        DataHandler $dataHandler
    ): void{
        if ($table !== 'tx_surfcampbase_api_endpoint') {
            return;
        }

        try {
            $uid = $this->getEndpointUid($id, $dataHandler);
            $endpoint = $this->endPointFactory->create($uid);
            $apiResponse = $this->apiClient->fetch($endpoint);
            $parsedResponseBody = $this->getEncodedResponseBody($apiResponse, $endpoint);
            $this->apiEndpointRepository->updateResponse($uid, $parsedResponseBody);
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            $this->displayError();
        }
    }

    protected function displayError(): void
    {
        $message = GeneralUtility::makeInstance(FlashMessage::class,
            'The API returned an error.',
            'Error fetching API',
            ContextualFeedbackSeverity::ERROR,
            true
        );

        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }

    protected function getEncodedResponseBody(ResponseInterface $response, RecordInterface $endpoint): string
    {
        $resolvedBody = $this->responseHandler->resolveResponseBody($response, $endpoint);
        return json_encode($resolvedBody, JSON_PRETTY_PRINT);
    }

    protected function getEndpointUid(string $uid, DataHandler $dataHandler): int
    {
        if (is_numeric($uid)) {
            return (int)$uid;
        }

        return (int)$dataHandler->substNEWwithIDs[$uid];
    }
}
