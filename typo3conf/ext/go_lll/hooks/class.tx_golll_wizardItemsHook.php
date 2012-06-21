<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012   Daniel Agro <agro@gosign.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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

require_once(PATH_typo3 . 'interfaces/interface.cms_newcontentelementwizarditemshook.php');

/**
 * hook for class db_new_content_el in templavoila
 *
 * @author Daniel Agro <agro@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_golll
 */
class tx_golll_wizardItemsHook implements cms_newContentElementWizardsHook {

	var $go_lllBElib;

	/**
	 * This function feeds a hook of templaVoila >= 1.6.0
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @author	Marius Stübs <marius@gosign.de>
	 *
	 * @desc Rewrite the "new element wizard Item List"
	 * @date 2012-01-26
	 *
	 * @return	void	In fact we return $wizardItems by reference.
	 */
	function manipulateWizardItems(&$wizardItems, &$self) {
		$this->go_lllBElib = t3lib_div::makeInstance('tx_golll_belib');
			// check if we are on a translation page. Page id is "$self->id".
			// if not: return.
		if( $self->id != $this->go_lllBElib->getTranslationPageUID() ) {
			return;
		}

			// fetch customized list of wizarditems
		$itemsArray = $this->go_lllBElib->getWizardItems();

			// build new wizarditems list and set an header
		$newWizardItemArray = array();
		$newWizardItemArray['common'] = array('header' => 'Bezeichner-Elemente');

		foreach ($itemsArray as $key => $item) {
			$newWizardItemArray['common_' . $key] = $item;
		}

			// overwrite the pld wizarditem list with our customized
		$wizardItems = $newWizardItemArray;

		// @reminder No return is required, because in fact we return $wizardItems by reference.
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_wizardItemsHook.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_wizardItemsHook.php']);
}
?>