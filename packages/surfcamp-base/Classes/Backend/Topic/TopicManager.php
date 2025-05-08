<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Topic;

readonly class TopicManager
{
    /**
     * @param TopicProviderInterface[] $providers
     */
    public function __construct(
        private iterable $providers
    ) {
    }

    public function getTopicConfiguration(string $topic): array
    {
        if(!$topic) {
            return [];
        }

        foreach ($this->providers as $provider) {
            if ($provider->supports($topic)) {
                return $provider->getConfiguration();
            }
        }

        throw new \InvalidArgumentException(sprintf('No configuration found for topic "%s".', $topic));
    }
}
