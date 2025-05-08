<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Topic;

class FootballTopicProvider implements TopicProviderInterface
{
    private const string TOPIC_NAME = 'football';

    public function supports(string $topic): bool
    {
        return $topic === self::TOPIC_NAME;
    }

    public function getConfiguration(): array
    {
        return [
            'fields' => [
                'goals' => self::FIELD_TYPE_STRING,
                'assists' => self::FIELD_TYPE_INTEGER,
            ],
        ];
    }
}
