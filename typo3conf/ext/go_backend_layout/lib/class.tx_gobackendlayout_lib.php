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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Backend library for the go_backend_layout extension.
 *
 * @author	Daniel Agro <agro@gosign.de>
 * @date	2012-06-14
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */
class tx_gobackendlayout_lib {

	/**
	 * This function manipulates the cType dropdown in
	 * the content element edit view
	 *
	 * @author:	Daniel Agro <agro@gosign.de>
	 * @date:	2012-06-14
	 *
	 * @param	array	$data: the data array as reference
	 *
	 * @return	void
	 */
	public function manipulateCTypeSelect(&$data) {
			// skip process if is admin or no items
		if ($GLOBALS['BE_USER']->isAdmin() || !is_array($data['items']) || tx_gobackendlayout_static::flexformRightsManagementDisabled()) {
			return;
		}

			// get templavoila field name
		$fieldName = tx_gobackendlayout_static::getFieldName($data['row']);

			// traverse the items
		foreach ($data['items'] as $key => $item) {
				// continue with next item if dropdown header
			if ($item[1] === '--div--') {
				continue;
			}

			if ($data['row']['CType'] === 'templavoila_pi1') {
					// if current element is 'templavoila_pi1', remove all items expect of 'templavoila_pi1'
				if ($item[1] !== 'templavoila_pi1') {
					unset($data['items'][$key]);
				}
			} elseif ($this->checkRemoveItem($fieldName, $item[1])) {
					// if no access or select item is templavoila_pi1, remove item
				unset($data['items'][$key]);
			}
		}
	}

	/**
	 * This function checks if the given item has to be removed
	 *
	 * @author:	Daniel Agro <agro@gosign.de>
	 * @date:	2012-06-15
	 *
	 * @param	string	$fieldName: the templavoila flexform field name
	 * @param	string	$cType: the CType to check if it has to be romved from dropdown in $fieldName
	 *
	 * @return	boolean	TRUE, if the cType hs to be removed
	 */
	public function checkRemoveItem($fieldName, $cType) {
		return (!tx_gobackendlayout_static::checkFieldAccess($fieldName, $cType, '0') || $cType === 'templavoila_pi1');
	}

}
?>