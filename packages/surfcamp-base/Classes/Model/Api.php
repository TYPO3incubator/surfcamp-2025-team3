<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Model;

class Api
{
    public function __construct(
        private readonly string $name,
        private readonly string $baseUrl,
        private readonly array $additionalHeaders,
        private readonly array $endpoints = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getAdditionalHeaders(): array
    {
        return $this->additionalHeaders;
    }

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }
}
