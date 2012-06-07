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
 * This class is a preprocess hook for t3lib_befunc->getProcessedValue to show labels of tx_dam files
 *
 * @author	Caspar Stuebs <caspar@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */
class tx_gobackendlayout_getProcessedValue {
	/**
	 * Preprocess hook for t3lib_befunc->getProcessedValue to show labels of tx_dam files
	 * It temporary changes the column config from 'group' to 'select', so the befunc is able to show the content
	 *
	 * @param	array	$params: The column config
	 * @param	array	$parentObject: dummy object, is NULL
	 *
	 * @return	void
	 */
	public function preProcessValue(&$params, &$parentObject) {
		if ($params['MM'] && $params['type'] == 'group' && $params['internal_type'] == 'db' && $params['allowed'] == 'tx_dam') {
			$params['type'] = 'select';
			$params['foreign_table'] = $params['allowed'];
		}
		return;
	}
}

?>