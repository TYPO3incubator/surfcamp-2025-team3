<?php

$LLL = 'LLL:EXT:plugsy/Resources/Private/Language/locallang_be.xlf:tx_surfcampbase_api_endpoint';

return [
    'ctrl' => [
        'title' => $LLL ,
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
        'iconfile' => 'EXT:plugsy/Resources/Public/Icons/api-endpoint.svg',
        'hideTable' => true,
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:setup/Resources/Private/Language/locallang.xlf:personal_data,
                base,
                name,
                path,
                cache_lifetime,
                type,
                body,
                response,
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
                hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:page.hidden_formlabel
            ',
        ],
        'hiddenLanguagePalette' => [
            'showitem' => 'sys_language_uid, l10n_parent',
            'isHiddenPalette' => true,
        ],
    ],
    'columns' => [
        'base' => [
            'exclude' => true,
            'label' => 'LLL:EXT:plugsy/Resources/Private/Language/locallang_be.xlf:tx_plugsy_api_base',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_plugsy_api_base',
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'name' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => $LLL . '.name',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'type' => [
            'label' => $LLL . '.type',
            'description' => $LLL . '.type.description',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'JSON/XML', 'value' => 'JSON/XML'],
                    ['label' => 'GraphQL', 'value' => 'GraphQL'],
                ]
            ]
        ],
        'body' => [
            'displayCond' => 'FIELD:type:=:GraphQL',
            'label' => $LLL . '.body',
            'config' => [
                'type' => 'text',
                'renderType' => 'codeEditor',
                'cols' => 50,
                'rows' => 10,
            ],
        ],
        'cache_lifetime' => [
            'label' => $LLL . '.cache_lifetime',
            'config' => [
                'type' => 'number',
            ],
        ],
        'path' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => $LLL . '.path',
            'description' => $LLL . '.path.description',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'response' => [
            'label' => $LLL . '.response',
            'description' => $LLL . '.response.description',
            'config' => [
                'type' => 'user',
                'renderType' => 'apiResponseField',
                'readOnly' => true,
            ],
        ],
        'mappings' => [
            'label' => 'LLL:EXT:plugsy/Resources/Private/Language/locallang_be.xlf:fieldmapping.title',
            'displayCond' => 'FIELD:response:REQ:true',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_plugsy_api_fieldmapping',
                'foreign_field' => 'api_endpoint',
                'appearance' => [
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'expandSingle' => true,
                    'collapseAll' => true,
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
];
