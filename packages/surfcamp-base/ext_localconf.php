<?php

defined('TYPO3') or die('Access denied.');

// Add default RTE configuration
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['surfcamp_base'] = 'EXT:surfcamp_base/Configuration/RTE/Default.yaml';

// Register the class to be available in 'eval' of TCA
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\TYPO3Incubator\SurfcampBase\Backend\Evaluation\ApiResponseEval::class] = '';
