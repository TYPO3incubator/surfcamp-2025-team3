<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\UserFunctions;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileRepository;

#[Autoconfigure(public: true)]
readonly class FileResolver
{
    public function __construct(private FileRepository $fileRepository)
    {
    }

    public function resolveContentsForFileReferenceLocalId(int|string $id): string
    {
        try {
            $reference = $this->fileRepository->findByRelation('tt_content', 'template', $id);
            if ($reference !== []) {
                return $reference[0]->getOriginalFile()->getContents();
            }
            return '';
        } catch (ResourceDoesNotExistException) {
            return '';
        }
    }
}
