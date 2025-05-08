<?php
declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Http\Client;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Domain\RecordInterface;

interface ClientInterface
{
    public function fetch(RecordInterface $endpoint): ResponseInterface;

    public function isResponsible(string $type): bool;
}
