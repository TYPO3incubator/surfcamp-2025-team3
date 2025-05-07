<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Model;

readonly class Api
{
    public function __construct(
        public string $name,
        public string $baseUrl,
        public array $additionalHeaders
    ) {
    }

    public function getApiUrl(string $endpointUrl): string
    {
        $baseUrl = str_ends_with($this->baseUrl, '/') ? $this->baseUrl : $this->baseUrl . '/';
        $endpointUrl = str_starts_with($endpointUrl, '/') ? substr($endpointUrl, 1) : $endpointUrl;
        return $baseUrl . $endpointUrl;
    }
}
