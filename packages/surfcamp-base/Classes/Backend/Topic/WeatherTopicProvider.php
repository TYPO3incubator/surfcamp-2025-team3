<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Topic;

class WeatherTopicProvider implements TopicProviderInterface
{
    private const string TOPIC_NAME = 'weather';

    public function supports(string $topic): bool
    {
        return $topic === self::TOPIC_NAME;
    }

    public function getConfiguration(): array
    {
        return [
            'fields' => [
                'temperature' => self::FIELD_TYPE_FLOAT,
                'humidity' => self::FIELD_TYPE_FLOAT,
                'air_quality' => self::FIELD_TYPE_FLOAT,
            ],
        ];
    }
}
