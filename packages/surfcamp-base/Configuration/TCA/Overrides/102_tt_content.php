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
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:field.api_endpoint',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_endpoint',
                'maxitems' => 1,
            ]
        ],
    ]
);

ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'CType',
    'api',
    'API',
    'after:fcbigfoot'
);
