<?php

defined('TYPO3') or die('Access denied.');

// Add default RTE configuration
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['surfcamp_base'] = 'EXT:surfcamp_base/Configuration/RTE/Default.yaml';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1746549562] = [
    'nodeName' => 'apiResponseField',
    'priority' => 40,
    'class' => \TYPO3Incubator\SurfcampBase\Backend\Form\Element\ApiResponseFieldElement::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    \TYPO3Incubator\SurfcampBase\Backend\Hooks\TtContentDataHandlerHook::class;
