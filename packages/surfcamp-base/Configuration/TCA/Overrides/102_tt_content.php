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
        'api_base' => [
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:field.api_name',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_base',
                'maxitems' => 1
            ],
            'onChange' => 'reload',
        ],
        'api_endpoint' => [
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:field.api_base',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_endpoint',
                'itemsProcFunc' => TYPO3Incubator\SurfcampBase\Backend\UserFunctions\DropdownItemsProvider::class . '->getApiEndpoints',
                'maxitems' => 1
            ],
            'onChange' => 'reload',
        ],
        'target_field_mapping' => [
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:field.target_field_mapping',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_fieldmapping',
                'itemsProcFunc' => TYPO3Incubator\SurfcampBase\Backend\UserFunctions\DropdownItemsProvider::class . '->getApiFieldMappings',
                'maxitems' => 1,
            ]
        ]
    ]
);

ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'CType',
    'api',
    'API',
    'after:fcbigfoot'
);
