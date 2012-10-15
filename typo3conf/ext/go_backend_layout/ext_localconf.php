<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

/*
 * HOOKS
 */

/*
 * hooks for css and js includes
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['typo3/template.php']['preHeaderRenderHook'][] = 'tx_gobackendlayout_addHeaderFilesToBackend->addJStoBackend';
$TYPO3_CONF_VARS['SC_OPTIONS']['typo3/template.php']['preHeaderRenderHook'][] = 'tx_gobackendlayout_addHeaderFilesToBackend->addCSStoBackend';

/*
 * render backend view of all content elements
 */
$TYPO3_CONF_VARS['EXTCONF']['templavoila']['mod1']['renderPreviewContentClass']['default'] = 'tx_gobackendlayout_previewContent';

/*
 * hook for ajax requests in new content element wizard
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['templavoila']['db_new_content_el']['wizardItemsHook'][] = 'tx_gobackendlayout_ajaxRequests';

/*
 * new content element wizard - manipulate item list
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['templavoila']['db_new_content_el']['wizardItemsHook'][] = 'tx_gobackendlayout_manipulateWizardItems';


/*
 * hook for ajax requests in page module (templavoila)
 */
$TYPO3_CONF_VARS['EXTCONF']['templavoila']['mod1']['renderTopToolbar'][] = 'tx_gobackendlayout_pageModulePreContent->handleAjaxRequest';

/*
 * hook for adding the notes as hidden fields which get fetched by js
 */
$TYPO3_CONF_VARS['EXTCONF']['templavoila']['mod1']['renderTopToolbar'][] = 'tx_gobackendlayout_pageModulePreContent->addhiddenNotes';

/*
 * preprocess hook for t3lib_befunc->getProcessedValue to show labels of tx_dam files
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['preProcessValue'][] = 'tx_gobackendlayout_getProcessedValue->preProcessValue';

/*
 * user auth recordEditAccessInternals() hook to check, if a element is editable by a user
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauthgroup.php']['recordEditAccessInternals'][] = 'tx_gobackendlayout_userAuthGroup->recordEditAccessInternals';

/*
 * XCLASSES
 */
/*
 * new page wizard - add doctype selector
 */
$TYPO3_CONF_VARS['BE']['XCLASS']['ext/templavoila/mod1/class.tx_templavoila_mod1_wizards.php'] = t3lib_extMgm::extPath('go_backend_layout') . 'xclass/class.ux_tx_templavoila_mod1_wizards.php';

/*
 * show dam thumbnails in db_list view
 */
$TYPO3_CONF_VARS['BE']['XCLASS']['typo3/class.db_list_extra.inc'] = t3lib_extMgm::extPath('go_backend_layout') . 'xclass/class.ux_db_list_extra.php';
/*
 * language switch for pages and pages_language_overlay
 */
$TYPO3_CONF_VARS['BE']['XCLASS']['typo3/alt_doc.php'] = t3lib_extMgm::extPath('go_backend_layout') . 'xclass/ux_alt_doc.php';

/*
 * pageTSconfig
 */
/*
 * add header to new content element wizard
 */
t3lib_extMgm::addPageTSConfig('
templavoila.wizards.newContentElement.wizardItems.common {
	elements {
		header {
			icon = gfx/c_wiz/regular_header.gif
			title = LLL:EXT:cms/layout/locallang_db_new_content_el.xml:common_headerOnly_title
			description = LLL:EXT:cms/layout/locallang_db_new_content_el.xml:common_headerOnly_description
			tt_content_defValues {
				CType = header
			}
		}
	}
	show := addToList(header)
}
');
/*
 * render new content element wizard with tabs
 */
t3lib_extMgm::addPageTSConfig('templavoila.wizards.newContentElement.renderMode = tabs' . "\n");
/*
 * show delete buttons instead of unlink buttons for content elements in templavoila
 */
t3lib_extMgm::addPageTSConfig('mod.web_txtemplavoilaM1.enableDeleteIconForLocalElements = 2' . "\n");
/*
 * show localization links for templavoila_pi1 content elements
 */
t3lib_extMgm::addPageTSConfig('mod.web_txtemplavoilaM1.enableLocalizationLinkForFCEs = 1' . "\n");
/*
 * hide reference buttons for content elements in templavoila
 */
t3lib_extMgm::addPageTSConfig('mod.web_txtemplavoilaM1.blindIcons = ref,browse' . "\n");
/*
 * show content of shortcut pages in page module
 */
t3lib_extMgm::addPageTSConfig('mod.web_txtemplavoilaM1.additionalDoktypesRenderToEditView = 4' . "\n");
/*
 * always show thumbnails
 */
t3lib_extMgm::addUserTSConfig('setup.override.thumbnailsByDefault = 1' . "\n");

?>