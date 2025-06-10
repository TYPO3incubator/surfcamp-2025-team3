<?php
declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Http\ContentTypeHandlers;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;

interface HandlerInterface
{
    public function resolveResponseBody(ResponseInterface $response): array;

    public function isResponsible(string $type): bool;
}
