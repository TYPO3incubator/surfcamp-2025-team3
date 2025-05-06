<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class ApiResponseFieldElement extends AbstractFormElement
{
    public function render(): array
    {
        $resultArray = [];
        $html = [];
        $resultArray['html'] = implode(LF, $html);
        return $resultArray;
    }
}
