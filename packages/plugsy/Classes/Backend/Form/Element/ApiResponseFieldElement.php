<?php

declare(strict_types=1);

namespace TYPO3Incubator\Plugsy\Backend\Form\Element;

use TYPO3\CMS\Backend\CodeEditor\CodeEditor;
use TYPO3\CMS\Backend\CodeEditor\Registry\AddonRegistry;
use TYPO3\CMS\Backend\Form\Element\CodeEditorElement;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class ApiResponseFieldElement extends CodeEditorElement
{
    public function render(): array
    {
        $this->resultArray = $this->initializeResultArray();
        $this->resultArray['javaScriptModules'][] = JavaScriptModuleInstruction::create('@typo3/backend/code-editor/element/code-mirror-element.js');

        // Compile and register code editor configuration
        GeneralUtility::makeInstance(CodeEditor::class)->registerConfiguration();

        $addonRegistry = GeneralUtility::makeInstance(AddonRegistry::class);
        $registeredAddons = $addonRegistry->getAddons();
        foreach ($registeredAddons as $addon) {
            foreach ($addon->getCssFiles() as $cssFile) {
                $this->resultArray['stylesheetFiles'][] = $cssFile;
            }
        }

        $parameterArray = $this->data['parameterArray'];

        $attributes = [
            'wrap' => 'off',
            'data-formengine-validation-rules' => $this->getValidationDataAsJsonString($parameterArray['fieldConf']['config']),
        ];
        if (isset($parameterArray['fieldConf']['config']['rows']) && MathUtility::canBeInterpretedAsInteger($parameterArray['fieldConf']['config']['rows'])) {
            $attributes['rows'] = $parameterArray['fieldConf']['config']['rows'];
        }

        $settings = [];
        if ($parameterArray['fieldConf']['config']['readOnly'] ?? false) {
            $settings['readonly'] = true;
        }

        $editorHtml = $this->getHTMLCodeForEditor(
            $parameterArray['itemFormElName'],
            'form-control font-monospace enable-tab',
            $parameterArray['itemFormElValue'],
            $attributes,
            $settings,
            [
                'target' => 0,
                'effectivePid' => $this->data['effectivePid'] ?? 0,
            ]
        );

        $fieldInformationResult = $this->renderFieldInformation();
        $fieldInformationHtml = $fieldInformationResult['html'];
        $this->resultArray = $this->mergeChildReturnIntoExistingResult($this->resultArray, $fieldInformationResult, false);

        $fieldControlResult = $this->renderFieldControl();
        $fieldControlHtml = $fieldControlResult['html'];
        $this->resultArray = $this->mergeChildReturnIntoExistingResult($this->resultArray, $fieldControlResult, false);

        $fieldWizardResult = $this->renderFieldWizard();
        $fieldWizardHtml = $fieldWizardResult['html'];
        $this->resultArray = $this->mergeChildReturnIntoExistingResult($this->resultArray, $fieldWizardResult, false);

        $html = [];
        $html[] = '<div class="formengine-field-item t3js-formengine-field-item">';
        $html[] = $fieldInformationHtml;
        // @todo move this save logic to fieldcontrol
        $html[] = $this->getSaveButtonHtml();
        $html[] = '<div class="form-control-wrap">';
        $html[] = '<div class="form-wizards-wrap">';
        $html[] = '<div class="form-wizards-item-element">';
        $html[] = $editorHtml;
        $html[] = '</div>';
        if (!empty($fieldControlHtml)) {
            $html[] = '<div class="form-wizards-item-aside form-wizards-item-aside--field-control">';
            $html[] = '<div class="btn-group">';
            $html[] = $fieldControlHtml;
            $html[] = '</div>';
            $html[] = '</div>';
        }
        if (!empty($fieldWizardHtml)) {
            $html[] = '<div class="form-wizards-item-bottom">';
            $html[] = $fieldWizardHtml;
            $html[] = '</div>';
        }
        $html[] = '</div>';
        $html[] = '</div>';
        $html[] = '</div>';

        $this->resultArray['html'] = $this->wrapWithFieldsetAndLegend(implode(LF, $html));

        return $this->resultArray;
    }

    protected function getSaveButtonHtml(): string
    {
        $path = PathUtility::getPublicResourceWebPath('EXT:core/Resources/Public/Icons/T3Icons/sprites/actions.svg');
        return sprintf(
            '<div class="form-group t3js-formengine-validation-marker t3js-inline-controls">
                        <button name="_savedok" class="btn btn-default " value="1" title="Save" form="EditDocumentController" aria-keyshortcuts="control+s">
                            <span class="t3js-icon icon icon-size-small icon-state-default icon-actions-document-save" data-identifier="actions-document-save" aria-hidden="true">
                                <span class="icon-markup">
                                    <svg class="icon-color"><use xlink:href="%s#actions-save"></use></svg>
                                </span>
                            </span>Fetch API Data</button>
                    </div>',
            $path
        );
    }
}
