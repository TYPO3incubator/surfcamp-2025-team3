<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Resource\Event\BeforeFileAddedEvent;

#[AsEventListener(
    identifier: 'surfcamp_base/move-html-file',
)]
readonly class MoveHtmlFile
{
    public function __invoke(BeforeFileAddedEvent $event): void
    {
        $foo = 'bar';
    }
}
