<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Http\Client;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TYPO3\CMS\Core\Domain\RecordInterface;

readonly class Client
{
    public function __construct(private iterable $clients)
    {
    }

    public function fetch(RecordInterface $endpoint, int $cacheLifetime = 600): array
    {
        $type = $endpoint->get('type');
        foreach ($this->clients as $client) {
            if ($client->isResponsible($type)) {
                return $client->fetch($endpoint, $cacheLifetime);
            }
        }

        throw new RuntimeException('No client found for type: ' . $type, 1746549106);
    }
}
