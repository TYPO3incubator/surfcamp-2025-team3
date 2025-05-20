<?php
declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Http\Client;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;

interface ClientInterface
{
    public function fetch(RecordInterface $endpoint, int $cacheLifetime): array;

    public function isResponsible(string $type): bool;
}
