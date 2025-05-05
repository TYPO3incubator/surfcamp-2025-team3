<?php
declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\UserFunc;

use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class FileResolver
{
    private ContentObjectRenderer $contentObjectRenderer;

    public function __construct(
        // @todo Dependency Injection here
    ) {}

    public function setContentObjectRenderer(ContentObjectRenderer $contentObjectRenderer): void
    {
        $this->contentObjectRenderer = $contentObjectRenderer;
    }

    public function resolveContentsForFileId(int|string $id): string
    {
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        try {
            return $resourceFactory->getFileObject((int)$id)->getContents();
        } catch (ResourceDoesNotExistException) {
            return '';
        }
    }

    public function resolveContentsForFileReferenceLocalId(int|string $id): string
    {
        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);

        try {
            $reference = $fileRepository->findByRelation('tt_content', 'template', $id);
            if ($reference !== []) {
                return $reference[0]->getOriginalFile()->getContents();
            }
            return '';
        } catch (ResourceDoesNotExistException) {
            return '';
        }
    }
}
