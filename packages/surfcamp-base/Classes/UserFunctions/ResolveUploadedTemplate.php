<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\UserFunctions;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\FileRepository;

#[Autoconfigure(public: true)]
readonly class ResolveUploadedTemplate
{
    public function __construct(private FileRepository $fileRepository)
    {
    }

    public function resolveTemplate(string $content, array $conf, ServerRequestInterface $request): string
    {
        $data = $request->getAttribute('currentContentObject')->data;
        $file = $this->fileRepository->findByRelation('tt_content', 'template', $data['uid']);

        return Environment::getPublicPath() . $file[0]->getOriginalFile()->getPublicUrl();
    }
}
