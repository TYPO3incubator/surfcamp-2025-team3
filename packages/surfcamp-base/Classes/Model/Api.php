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
}
