<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Service;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

readonly class CacheService
{
    public function __construct(
        private FrontendInterface $cache,
    ) {}

    public function get(string $identifier): array|bool
    {
        return $this->cache->get($identifier);
    }

    public function set(string $identifier, array $data, int $lifetime): void
    {
        $this->cache->set($identifier, $data, [], $lifetime);
    }
}
