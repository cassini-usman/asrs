<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

/*
 * HOOKS
 */
/*
 * render backend view of all content elements
 */
$TYPO3_CONF_VARS['EXTCONF']['templavoila']['mod1']['renderPreviewContentClass']['default'] = 'tx_gobackendlayout_previewContent';
/*
 * new content element wizard - manipulate item list
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['templavoila']['db_new_content_el']['wizardItemsHook'][]  = 'tx_gobackendlayout_manipulateWizardItems';

/*
 * XCLASSES
 */
/*
 * new page wizard - add doctype selector
 */
$TYPO3_CONF_VARS['BE']['XCLASS']['ext/templavoila/mod1/class.tx_templavoila_mod1_wizards.php'] = t3lib_extMgm::extPath('go_backend_layout').'xclass/class.ux_tx_templavoila_mod1_wizards.php';

/*
 * show dam thumbnails in db_list view
 */
$TYPO3_CONF_VARS['BE']['XCLASS']['typo3/class.db_list_extra.inc'] = t3lib_extMgm::extPath('go_backend_layout').'xclass/class.ux_db_list_extra.php';
/*
 * language switch for pages and pages_language_overlay
 */
$TYPO3_CONF_VARS['BE']['XCLASS']['typo3/alt_doc.php'] = t3lib_extMgm::extPath('go_backend_layout').'xclass/ux_alt_doc.php';

/*
 * pageTSconfig
 */
/*
 * render new content element wizard with tabs
 */
t3lib_extMgm::addPageTSConfig('templavoila.wizards.newContentElement.renderMode = tabs' . "\n");
/*
 * show delete buttons instead of unlink buttons for content elements in templavoila
 */
t3lib_extMgm::addPageTSConfig('mod.web_txtemplavoilaM1.enableDeleteIconForLocalElements = 2' . "\n");
/*
 * hide reference buttons for content elements in templavoila
 */
t3lib_extMgm::addPageTSConfig('mod.web_txtemplavoilaM1.blindIcons = ref' . "\n");
/*
 * show content of shortcut pages in page module
 */
t3lib_extMgm::addPageTSConfig('mod.web_txtemplavoilaM1.additionalDoktypesRenderToEditView = 4' . "\n");
/*
 * always show thumbnails
 */
t3lib_extMgm::addUserTSConfig('setup.override.thumbnailsByDefault = 1' . "\n");

?>