<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:tx_surfcampbase_api_endpoint',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'sortby' => 'sorting',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name',
        'iconfile' => 'EXT:surfcamp_base/Resources/Public/Icons/api-endpoint.svg',
        'hideTable' => true,
    ],
    'columns' => [
        'base' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_base',
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'name' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:tx_surfcampbase_api_endpoint.name',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'path' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:tx_surfcampbase_api_endpoint.path',
            'description' => 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf:tx_surfcampbase_api_endpoint.path.description',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'mappings' => [
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_surfcampbase_api_fieldmapping',
                'foreign_field' => 'api_endpoint',
                'appearance' => [
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'expandSingle' => true,
                    'enabledControls' => [
                        'localize' => true,
                        'info' => false,
                        'dragdrop' => true,
                        'sort' => true,
                        'hide' => true,
                        'delete' => true,
                        'new' => true,
                    ],
                ],
                'behaviour' => [
                    'mode' => 'select',
                ],
            ],
        ]
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:setup/Resources/Private/Language/locallang.xlf:personal_data,
                base,
                name,
                path,
                mappings,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.visibility;visibility,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
                --palette--;;hiddenLanguagePalette,
            ',
        ],
    ],
    'palettes' => [
        'access' => [
            'showitem' => '
                    starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
                    endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel
                ',
        ],
        'visibility' => [
            'showitem' => '
                hidden;LLL:EXT:vrr_customcontentelements/Resources/Private/Language/locallang.xlf:timeline_item
            ',
        ],
        'hiddenLanguagePalette' => [
            'showitem' => 'sys_language_uid, l10n_parent',
            'isHiddenPalette' => true,
        ],
]
];
