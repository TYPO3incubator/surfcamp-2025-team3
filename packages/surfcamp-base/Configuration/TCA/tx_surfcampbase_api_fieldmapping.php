<?php

$LLL = 'LLL:EXT:surfcamp_base/Resources/Private/Language/locallang_be.xlf';

return [
    'ctrl' => [
        'title' => $LLL . ':fieldmapping.title',
        'label' => 'source',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'delete' => 'deleted',
        'sortby' => 'crdate',
        'searchFields' => '',
        'iconfile' => 'EXT:surfcamp_base/Resources/Public/Icons/Extension.svg',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'columns' => [
        'api_endpoint' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_endpoint',
                'maxitems' => 1,
            ]
        ],
        'source' => [
            'label' => $LLL . ':fieldmapping.source.title',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'target' => [
            'label' => $LLL . ':fieldmapping.target.title',
            'config' => [
                'type' => 'input',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'target_datatype' => [
            'label' => $LLL . ':fieldmapping.target_datatype.title',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'maxitems' => 1,
                'items' => [
                    [
                        $LLL . ':fieldmapping.target_datatype.items.string',
                        'string'
                    ],
                    [
                        $LLL . ':fieldmapping.target_datatype.items.int',
                        'int'
                    ],
                    [
                        $LLL . ':fieldmapping.target_datatype.items.float',
                        'float'
                    ],
                    [
                        $LLL . ':fieldmapping.target_datatype.items.bool',
                        'bool'
                    ],
                    [
                        $LLL . ':fieldmapping.target_datatype.items.currency',
                        'currency'
                    ],
                    [
                        $LLL . ':fieldmapping.target_datatype.items.datetime',
                        'datetime'
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ]
    ],
    'palettes' => [
        'mapping_details' => [
            'showitem' => '
                api_endpoint,
                source,
                --linebreak--,
                target,
                target_datatype,
            '
        ],
    ],
    'types' => [
        0 => [
            'showitem' => '
                --palette--;;mapping_details,'
        ],
    ],
];
