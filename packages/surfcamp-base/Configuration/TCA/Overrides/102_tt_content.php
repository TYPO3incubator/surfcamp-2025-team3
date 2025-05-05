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
                'maxitems' => 1,
            ],
        ],
        'api_endpoint' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_endpoint',
                'maxitems' => 1,
            ]
        ],
    ]
);
