<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Model;

class Api
{
    private string $name;
    private string $baseUrl;
    private array $additionalHeaders;
    private array $endpoints;

    public function __construct(string $name, string $baseUrl, array $additionalHeaders, array $endpoints = [])
    {
        $this->name = $name;
        $this->baseUrl = $baseUrl;
        $this->additionalHeaders = $additionalHeaders;
        $this->endpoints = $endpoints;
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
