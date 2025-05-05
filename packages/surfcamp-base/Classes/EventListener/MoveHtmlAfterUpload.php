<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\EventListener;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Resource\Event\BeforeFileCreatedEvent;

#[AsEventListener(
    identifier: 'surfcamp-base/move-html-after-upload',
)]
#[Autoconfigure(public: true)]
class MoveHtmlAfterUpload
{
    public function __invoke(BeforeFileCreatedEvent $event): void
    {
        die('');
    }
}
