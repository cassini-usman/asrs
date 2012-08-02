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

/**
 *
 * @author		Daniel Agro <agro@gosign.de>
 * @date		2012-07-13
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */

class ext_update {

	/**
	 * main function which is called by the typo3 ext manager
	 *
	 * @date 2012-07-13
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @return	string	the HTML output, which is displayed in the update window
	 */
	public function main () {
		$return = '';

			// update for fieldrights updates
		if ($this->checkTableExists('gbl_fieldrights')) {
				// first check if new table exists
			if (!$this->checkTableExists('tx_gobackendlayout_fieldrights')) {
				$return .= 'fieldrights: please do a database compare before updating extension <br />';
			} else {
					// transform the data from old to new table
				$this->transformFieldrightsTable();
					// drop the old table
				$GLOBALS['TYPO3_DB']->sql_query('DROP TABLE gbl_fieldrights');
				$return .= 'fieldrights: new table updated <br />';
				$return .= 'fieldrights: old table dropped <br />';
			}
		}
			// update for notes updates
		if ($this->checkTableExists('gbl_notes')) {
					// just drop the old table
			$GLOBALS['TYPO3_DB']->sql_query('DROP TABLE gbl_notes');
			$return .= 'notes: table dropped <br />';
		}

		return $return;
	}

	/**
	 * this function is called by the typo3 ext manager and checks if
	 * any update is necessary
	 *
	 * @date 2012-07-13
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @return	boolean	TRUE if update is necessary
	 */
	public function access() {
		$access = FALSE;

			// update is necessary if old fieldrights table exists
		if ($this->checkTableExists('gbl_fieldrights')) {
			$access |= TRUE;
		}

			// update is necessary if old notes table exists
		if ($this->checkTableExists('gbl_notes')) {
			$access |= TRUE;
		}

		return $access;
	}

	/**
	 * this function checks if the given table exists
	 *
	 * @date 2012-07-13
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	string	$tableName: the name of the table
	 *
	 * @return	boolean	TRUE if table exists
	 */
	private function checkTableExists($tableName) {
		return array_key_exists($tableName, $GLOBALS['TYPO3_DB']->admin_get_tables());
	}

	/**
	 * this function transforms the data from the old
	 * to the new fieldrights table
	 *
	 * @date 2012-07-13
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 *
	 * @return	void
	 */
	public function transformFieldrightsTable($tableName) {
			// get the data from the old table
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'gbl_fieldrights', '', '', '', '');

			// travers the fetched data
		foreach ($rows as $row) {
				// ignore record if deleted flag is set
			if ($row['deleted']) {
				continue;
			}

			$elementKeyArray = t3lib_div::trimExplode('_', $row['elementKey']);

				// fce is now templavoila
				// templateobject is stored seperate
			if ($elementKeyArray[0] === 'fce') {
				$elementKey = 'templavoila_pi1';
				$templateObject = $elementKeyArray[1];
			} else {
				$elementKey = $row['elementKey'];
				$templateObject = 0;
			}

				// continue if row already exists
			$accessRow = tx_gobackendlayout_static::getAccessRow($row['fieldName'], $elementKey, $templateObject);
			if (!empty($accessRow)) {
				continue;
			}

				// insert the mapped data
			$insert = array(
				'fieldName' => $row['fieldName'],
				'elementKey' => $elementKey,
				'templateObject' => $templateObject,
				'access' => 'true',
			);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_gobackendlayout_fieldrights', $insert, FALSE);
		}
	}
}
?>