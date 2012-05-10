<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Caspar Stuebs (caspar@gosign.de)
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
 * XCLASS for the local record list to show dam thumbs in db_list view
 *
 * @author		Caspar Stuebs <caspar@gosign.de>
 * @package		TYPO3
 * @subpackage	tx_gobackendlayout
 */
class ux_localRecordList extends localRecordList {
	/**
	 * Create thumbnail code for record/field
	 *
	 * @param	array		Record array
	 * @param	string		Table (record is from)
	 * @param	string		Field name for which thumbsnail are to be rendered.
	 * @return	string		HTML for thumbnails, if any.
	 */
	function thumbCode($row, $table, $field) {
		t3lib_div::loadTCA($table);
		if ($GLOBALS['TCA'][$table]['columns'][$field]['config']['MM'] == 'tx_dam_mm_ref' && is_numeric($row[$field])) { // if it is a dam image
				// get the dam image and put file link into the row, so that thumbCode can handle it
			$damFiles = tx_dam_db::getReferencedFiles($table, $row['uid'], $field, 'tx_dam_mm_ref', '', array(), '', 'sorting_foreign');
			if (is_array($damFiles) && count($damFiles) > 0) {
				$row[$field] = implode(',', $damFiles['files']);
			}
		}
		return parent::thumbCode($row,$table,$field,$this->backPath,$this->thumbScript);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/class.ux_localRecordList.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/class.ux_localRecordList.php']);
}

?>