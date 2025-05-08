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
        'searchFields' => 'source,target',
        'iconfile' => 'EXT:surfcamp_base/Resources/Public/Icons/Extension.svg',
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
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
    'columns' => [
        'api_endpoint' => [
            'exclude' => true,
            'label' => $LLL . ':tx_surfcampbase_api_endpoint',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_surfcampbase_api_endpoint',
                'maxitems' => 1,
            ]
        ],
        'source' => [
            'label' => $LLL . ':fieldmapping.source.title',
            'description' => $LLL . ':fieldmapping.source.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'foreign_table' => 'tx_surfcampbase_api_endpoint',
                'size' => 20,
                'maxitems' => 1,
                'items' => [
                    ['label' => 'Default value', 'value' => 'default'],
                ],
                'treeConfig' => [
                    'parentField' => 'pid',
                    'appearance' => [
                        'expandAll' => true,
                        'showHeader' => true,
                    ],
                ],
                'itemsProcFunc' => TYPO3Incubator\SurfcampBase\Backend\UserFunctions\SelectAPIResponseKeys::class . '->selectAPIResponseKeys',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'target' => [
            'label' => $LLL . ':fieldmapping.target.title',
            'description' => $LLL . ':fieldmapping.target.description',
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
                    [
                        $LLL . ':fieldmapping.target_datatype.items.array',
                        'array'
                    ],
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ]
    ],
];
