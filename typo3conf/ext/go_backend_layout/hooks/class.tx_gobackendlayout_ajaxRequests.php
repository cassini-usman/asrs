<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Daniel Agro <agro@gosign.de>
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
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
 * New content elements wizard hook for templavoila
 * Used to handel ajax requests
 *
 * @author		Daniel Agro <agro@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */

class tx_gobackendlayout_ajaxRequests implements cms_newContentElementWizardsHook {

	/**
	 * This function adds the needed javascript to the wizard and handles the ajax requests
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 *
	 * @param	array	$wizardItems: The wizard item as reference
	 * @param	array	$parentObject: The templavoila wizard object as reference
	 *
	 * @return	void
	 */
	public function manipulateWizardItems(&$wizardItems, &$parentObject) {
		tx_gobackendlayout_static::addJavaScriptFile('res/javascript/fieldRights.js');
		$this->postData = array();
		if ($GLOBALS['BE_USER']->isAdmin()) {
			$this->postData = array_merge(t3lib_div::_GET(), t3lib_div::_POST());
			if($this->postData['isAjax']) {
				switch($this->postData['action']) {
					case 'rightsAction':
						$this->handleRightsAction();
						break;
				}
				die();
			}
		}
	}

	/**
	 * handles the change rights action
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * date		2012-06-05
	 *
	 * @return	void
	 */

	private function handleRightsAction() {
		if($this->postData['check'] === 'true') {
			tx_gobackendlayout_static::grantFieldAccess($this->postData['field'], $this->postData['cType'], $this->postData['templateObject']);
		} else {
			tx_gobackendlayout_static::revokeFieldAccess($this->postData['field'], $this->postData['cType'], $this->postData['templateObject']);
		}
	}
}

?>