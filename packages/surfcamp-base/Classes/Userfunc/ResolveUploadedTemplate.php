<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Userfunc;

use Psr\Http\Message\ServerRequestInterface;

class ResolveUploadedTemplate
{
    public function resolveTemplate(string $content, array $conf, ServerRequestInterface $request): string
    {
        $foo = 'bar';
    }
}
