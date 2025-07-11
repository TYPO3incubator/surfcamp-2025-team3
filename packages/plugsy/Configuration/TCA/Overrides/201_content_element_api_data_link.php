<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

$key = 'api_data_link';

// Adds the content element to the "Type" dropdown
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => 'LLL:EXT:plugsy/Resources/Private/Language/locallang_be.xlf:content_element.api_data_link.label',
        'value' => $key,
        'description' => 'LLL:EXT:plugsy/Resources/Private/Language/locallang_be.xlf:content_element.api_data_link.description',
        'group' => 'api',
    ],
);

// Configure the default backend fields for the content element
$GLOBALS['TCA']['tt_content']['types'][$key] = [
    'showitem' => '
        header,    
        template,
        api_endpoint,
    ',
];
