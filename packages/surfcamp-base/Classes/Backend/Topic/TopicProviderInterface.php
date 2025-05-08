<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Topic;

interface TopicProviderInterface
{
    public const string FIELD_TYPE_STRING = 'string';
    public const string FIELD_TYPE_INTEGER = 'int';
    public const string FIELD_TYPE_FLOAT = 'float';
    public const string FIELD_TYPE_BOOLEAN = 'bool';
    public const string FIELD_TYPE_CURRENCY = 'currency';
    public const string FIELD_TYPE_DATETIME = 'datetime';
    public const string FIELD_TYPE_ARRAY = 'array';

    public function supports(string $topic): bool;

    public function getConfiguration(): array;
}
