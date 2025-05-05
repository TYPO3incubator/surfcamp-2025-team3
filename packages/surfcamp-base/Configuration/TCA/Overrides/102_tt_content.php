<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    [
        'template' => [
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:field.template',
            'config' => [
                'type' => 'file',
                'allowed' => 'html',
            ],
        ]
    ]
);
