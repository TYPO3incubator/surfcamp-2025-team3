<?php

defined('TYPO3') or die('Access denied.');

// Add default RTE configuration
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['plugsy'] = 'EXT:plugsy/Configuration/RTE/Default.yaml';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1746549562] = [
    'nodeName' => 'apiResponseField',
    'priority' => 40,
    'class' => \TYPO3Incubator\Plugsy\Backend\Form\Element\ApiResponseFieldElement::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['plugsy_api_response'] = [
    'groups' => ['pages'],
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    \TYPO3Incubator\Plugsy\Hooks\DataHandlerHook::class;
