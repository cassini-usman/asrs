<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Caspar Stuebs <caspar@gosign.de>
*  All rights reserved
*
*  script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * XCLASS for Submodule 'Wizards' for the templavoila page module
 *
 * Note: This class is closely bound to the page module class and uses many variables and functions directly. After major modifications of
 *       the page module all functions of this wizard class should be checked to make sure that they still work.
 *
 * @author		Caspar Stuebs <caspar@gosign.de>
 * @package		TYPO3
 * @subpackage	tx_gobackendlayout
 */
class ux_tx_templavoila_mod1_wizards extends tx_templavoila_mod1_wizards {

	/********************************************
	 *
	 * Wizard related helper functions
	 *
	 ********************************************/

	/**
	 * Renders the template selector.
	 *
	 * @param	integer		Position id. Can be positive and negative depending of where the new page is going: Negative always points to a position AFTER the page having the abs. value of the positionId. Positive numbers means to create as the first subpage to another page.
	 * @param	string		$templateType: The template type, 'tmplobj' or 't3d'
	 * @return	string		HTML output containing a table with the template selector
	 */
	function renderTemplateSelector ($positionPid, $templateType='tmplobj') {
		$content = parent::renderTemplateSelector($positionPid, $templateType);

		if ($content) {
				// add Non-Templavoila-Options (DOKTYPE) ---  by elio@gosign 31-05-2011
			t3lib_div::loadTCA('pages');
				// create a standard TCE-Form-Element
			$this->tceforms = t3lib_div::makeInstance('t3lib_TCEforms');
			$this->tceforms->backPath = $GLOBALS['BACK_PATH'];
			$this->tceforms->initDefaultBEMode();

				// get config from TCA
			$conf = array(
					'itemFormElName' => 'data[doktype]',
					'fieldConf' => array('config' => $GLOBALS['TCA']['pages']['columns']['doktype']['config']),
			);
				// render and wrap the Field
			$doktype = $this->tceforms->getSingleField_typeSelect('pages', 'doktype', array('doktype' => 1), $conf);
			$doktype = '<label style="font-size:12px;padding:0 5px;">' . $GLOBALS['LANG']->sL($GLOBALS['TCA']['pages']['columns']['doktype']['label']) . '</label>' . $doktype;
			$doktype = '<div class="doktypeBox" style="padding-bottom:15px;">' . $doktype . '</div>';

				/*
				 * @TODO: Look at TCA, if the template object is shown in backend and show/hide the selector due to that info.
				 *
				 * At this moment only for 'doktype = 1' the template selection is shown and it is hardcoded in javascript file 'res/javascript/newPageWizard.js'
				 */
				// add javascript to show / hide template selector due to doktype.
			tx_gobackendlayout_static::addJavaScriptFile('res/javascript/newPageWizard.js', 'go_backend_layout');

			$content = $doktype . $content;
		}

		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/class.ux_tx_templavoila_mod1_wizards.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/class.ux_tx_templavoila_mod1_wizards.php']);
}

?>