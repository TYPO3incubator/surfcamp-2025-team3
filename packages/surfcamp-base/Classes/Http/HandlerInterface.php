<?php
declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Core\Domain\RecordInterface;

interface HandlerInterface
{
    public function map(ResponseInterface $response, RecordInterface $endpoint): array;

    public function isResponsible(string $type): bool;
}
