<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Caspar Stuebs <caspar@gosign.de>
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

/**
 * This class is a postprocess hook for t3lib_userAuthGroup->recordEditAccessInternals to check, if a record could be edited
 *
 * @author	Caspar Stuebs <caspar@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */
class tx_gobackendlayout_userAuthGroup {
	/**
	 * Postprocess hook to check, if a record could be edited
	 *
	 * @param	array	$params: Contains the record to check
	 * @param	object	$parentObject: t3lib_userAuthGroup
	 *
	 * @return	boolean	TRUE, if record could be edited
	 */
	public function recordEditAccessInternals(&$params, &$parentObject) {
			// if no uid is set, we have a new record.
			// for this new record, the access is already checked in the new content element wizard
			// so first, we dont have to check againg, second, we can´t check if we don´t have an uid
		if ($params['table'] !== 'tt_content'
				|| !isset($params['idOrRow']['uid'])
				|| $this->checkIfTranslationPage($params['idOrRow']['pid'])) {
			return TRUE;
		}
			// get templavoila field name
		$fieldName = tx_gobackendlayout_static::getFieldName($params['idOrRow']);
		$tvTemplateObject = $params['idOrRow']['tx_templavoila_to'] ? (int) $params['idOrRow']['tx_templavoila_to'] : 0;

		return tx_gobackendlayout_static::checkFieldAccess($fieldName, $params['idOrRow']['CType'], $tvTemplateObject);
	}

	/**
	 * This function checks if we are on a translation page (go_lll label page)
	 *
	 * @param	int	$pid: pid to check
	 *
	 * @return	boolean	TRUE, we are on the translation page
	 */
	protected function checkIfTranslationPage($pid) {
		$return = FALSE;

		if (t3lib_extMgm::isLoaded('go_lll')) {
			$this->go_lllBElib = t3lib_div::makeInstance('tx_golll_belib');
			$return |= $pid == $this->go_lllBElib->getTranslationPageUID();
		}

		return $return;
	}
}

?>