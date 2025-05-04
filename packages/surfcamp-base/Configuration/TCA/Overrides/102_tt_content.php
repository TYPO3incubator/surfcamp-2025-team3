<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    [
        'template' => [
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:field.layout',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_file',
                'foreign_table_where' => 'AND sys_file.storage = 2 ORDER BY uid',
            ],
        ]
    ]
);
