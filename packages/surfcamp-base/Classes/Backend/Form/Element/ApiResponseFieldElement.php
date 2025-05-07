<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Form\Element;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use TYPO3\CMS\Backend\Form\Element\CodeEditorElement;
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampBase\Factory\ApiFactory;
use TYPO3Incubator\SurfcampBase\Factory\EndPointFactory;
use TYPO3Incubator\SurfcampBase\Http\Client\ApiClient;
use TYPO3Incubator\SurfcampBase\Http\ContentTypeHandlers\ResponseHandler;

class ApiResponseFieldElement extends CodeEditorElement
{
    public function __construct(
        private readonly ApiClient $apiClient,
        private readonly ResponseHandler $responseHandler,
        private readonly EndPointFactory $endPointFactory,
        private readonly ApiFactory $apiFactory,
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
            $apiResponse = $this->getApiResponse($endpoint);
            $parsedResponseBody = $this->getResponseBody($apiResponse, $endpoint);
            $this->data['parameterArray']['itemFormElValue'] = json_encode($parsedResponseBody, JSON_PRETTY_PRINT);
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            $this->displayError();
        }

        return parent::render();
    }

    /**
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getApiResponse(RecordInterface $endpoint): ResponseInterface
    {
        $base = $this->apiFactory->create($endpoint->get('base') ?? 0);

        return $this->apiClient->get($base->getApiUrl($endpoint->get('path') ?? ''),
            $base->additionalHeaders ?? []
        );
    }

    protected function getResponseBody(ResponseInterface $response, RecordInterface $endpoint): array
    {
        return $this->responseHandler->resolveResponseBody($response, $endpoint);
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
