<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

/*
 * new content element wizard - manipulate item list
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['templavoila']['db_new_content_el']['wizardItemsHook'][] = t3lib_extMgm::extPath('go_lll').'hooks/class.tx_golll_wizardItemsHook.php:tx_golll_wizardItemsHook';

 /*
 * redirect to the edit view of an go_lll_piLabel element if the user wants to to create it again
 */
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['templavoila']['mod1']['handleIncomingCommands'][] = t3lib_extMgm::extPath('go_lll').'hooks/class.tx_golll_handleIncomingCommands.php:tx_golll_handleIncomingCommands';

 /*
 * Call writeLabelsToDatabase() if CType "go_lll_piLabel" is edited
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass']['go_news'] = t3lib_extMgm::extPath('go_lll').'hooks/class.tx_golll_getMainFieldsClass.php:tx_golll_getMainFieldsClass';

 /*
 * apply the user sorting to the inline element
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = t3lib_extMgm::extPath('go_lll').'hooks/class.tx_golll_getSingleFieldClass.php:tx_golll_getSingleFieldClass';

?>