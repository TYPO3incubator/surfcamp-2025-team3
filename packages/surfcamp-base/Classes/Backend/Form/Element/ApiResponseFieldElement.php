<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampBase\Backend\Form\Element;

use TYPO3\CMS\Backend\Form\Element\CodeEditorElement;

class ApiResponseFieldElement extends CodeEditorElement
{
    public function render(): array
    {
        $this->data['parameterArray']['itemFormElValue'] = 'computed value';
        return parent::render();
    }
}
