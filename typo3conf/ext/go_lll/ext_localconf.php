<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// #
// ### Wizardlist of Contentelements
// #
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['templavoila']['db_new_content_el']['wizardItemsHook'][] = t3lib_extMgm::extPath('go_lll').'lib/class.tx_golll_userfuncs.php:tx_golll_userfuncs';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['templavoila']['mod1']['handleIncomingCommands'][] = t3lib_extMgm::extPath('go_lll').'lib/class.tx_golll_userfuncs.php:tx_golll_userfuncs';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass']['go_news'] = t3lib_extMgm::extPath('go_lll').'lib/class.tx_golll_userfuncs.php:tx_golll_userfuncs';

?>