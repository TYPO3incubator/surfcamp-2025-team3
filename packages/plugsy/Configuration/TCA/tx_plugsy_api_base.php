<?php

$ll = 'LLL:EXT:plugsy/Resources/Private/Language/locallang_be.xlf:tx_plugsy_api_base';

return [
    'ctrl' => [
        'title' => $ll,
        'label' => 'name',
        'iconfile' => 'EXT:plugsy/Resources/Public/Icons/configuration.svg',
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
    ],
    'types' => [
        0 => [
            'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                name,base_url,additional_headers,endpoints,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                hidden,--palette--;;timeRestriction,',
        ],
    ],
    'columns' => [
        'name' => [
            'label' => $ll . '.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 200,
            ],
        ],
        'base_url' => [
            'label' => $ll . '.base_url',
            'description' => $ll . '.base_url.description',
            'config' => [
                'type' => 'link',
                'required' => true,
                'allowedTypes' => ['url'],
            ],
        ],
        'additional_headers' => [
            'label' => $ll . '.additional_headers',
            'description' => $ll . '.additional_headers.description',
            'config' => [
                'type' => 'json',
            ],
        ],
        'endpoints' => [
            'label' => $ll . '.endpoints',
            'description' => $ll . '.endpoints.description',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_plugsy_api_endpoint',
                'foreign_field' => 'base',
                'appearance' => [
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'expandSingle' => true,
                    'collapseAll' => false,
                ],
            ],
        ],
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
    ],
];
