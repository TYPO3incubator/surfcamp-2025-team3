<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Form\Element;

use Psr\Http\Message\ResponseInterface;
use Throwable;
use TYPO3\CMS\Backend\Form\Element\CodeEditorElement;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Http\Client\Client;
use TYPO3Incubator\SurfcampBase\Http\ContentTypeHandlers\ResponseHandler;
use TYPO3Incubator\SurfcampBase\Repository\ApiEndpointRepository;

class ApiResponseFieldElement extends CodeEditorElement
{
    public function __construct(
        private readonly Client $apiClient,
        private readonly ResponseHandler $responseHandler,
        private readonly EndPointFactory $endPointFactory,
        private readonly ApiEndpointRepository $apiEndpointRepository,
    ) {
    }

    public function render(): array
    {
        if ($this->data['tableName'] !== 'tx_surfcampbase_api_endpoint') {
            return parent::render();
        }

        $uid = (int)($this->data['vanillaUid'] ?? 0);

        try {
            $endpoint = $this->endPointFactory->create($uid);
            $apiResponse = $this->apiClient->fetch($endpoint);
            $parsedResponseBody = $this->getEncodedResponseBody($apiResponse, $endpoint);
            $this->apiEndpointRepository->updateResponse($uid, $parsedResponseBody);
            $this->data['parameterArray']['itemFormElValue'] = $parsedResponseBody;
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            $this->displayError();
        }

        return parent::render();
    }

    protected function getEncodedResponseBody(ResponseInterface $response, RecordInterface $endpoint): string
    {
        $resolvedBody = $this->responseHandler->resolveResponseBody($response, $endpoint);
        return json_encode($resolvedBody, JSON_PRETTY_PRINT);
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
}
