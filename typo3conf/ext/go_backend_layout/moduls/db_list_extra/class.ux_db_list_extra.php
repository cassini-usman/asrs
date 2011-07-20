<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2009 Kasper Skårhøj (kasperYYYY@typo3.com)
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
 * Include file extending t3lib_recordList
 * Shared between Web>List (db_list.php) and Web>Page (sysext/cms/layout/db_layout.php)
 *
 * $Id: class.db_list.inc 9847 2010-12-17 23:26:59Z steffenk $
 * Revised for TYPO3 3.6 December/2003 by Kasper Skårhøj
 * XHTML compliant
 *
 * @author	Elio Wahlen <vorname@gosign.de>
 */
 
class ux_localRecordList extends localRecordList{
	/**
	 * Makes the list of fields to select for a table
	 *
	 * @param	string		Table name
	 * @param	boolean		If set, users access to the field (non-exclude-fields) is NOT checked.
	 * @param	boolean		If set, also adds crdate and tstamp fields (note: they will also be added if user is admin or dontCheckUser is set)
	 * @return	array		Array, where values are fieldnames to include in query
	 */
	
	// THUMB HANDLING

	var $DAMimageFields = array();
	
	function getTable($table,$id,$rowlist)	{
		global $TCA;
		t3lib_div::loadTCA($table);
		foreach( $TCA[$table]['columns'] as $key => $col ) { // Get all DAM-Fields from this table
			if ( $col['config']['allowed'] == 'tx_dam' ) {
				$this->DAMimageFields[$table][] = $key;
			}
		}
		// normal handling
		return parent::getTable($table,$id,$rowlist);
	}

	function renderListRow($table,$row,$cc,$titleCol,$thumbsCol,$indent=0)	{
		// die hier benutzten Felder müssen zuvor (durch den hook db_list_hook.php) geladen werden.
		// die Felder müssen an zwei Stellen konfiguriert werden
		if (t3lib_extMgm::isLoaded('dam_ttcontent') && empty($row['image']) ) { // replace normal thumb by DAM-thumb
			foreach ( $this->DAMimageFields as $damField) {
				if ( $row[$damField] ) {
					$thumbsCol = $damField;
					break;
				}
			}
			$thumbsCol = 'tx_damttcontent_files';
			$this->thumbsCol = $thumbsCol; // for use in thumbCode()
		}
		$this->thumbs = 1; // enable thumbs generally
		$html = parent::renderListRow($table,$row,$cc,$titleCol,$thumbsCol,$indent);
		return $html;
	}
	
	function thumbCode($row,$table,$field) { // this is done for all db_list items
		if ( t3lib_extMgm::isLoaded('dam_ttcontent') && is_numeric($row[$this->thumbsCol])) { // if it is a dam image
			// get the dam image and put file link into the row, so that thumbCode can handle it
			$damFiles = tx_dam_db::getReferencedFiles('tt_content', $row['uid'], $this->thumbsCol, 'tx_dam_mm_ref', '', array(), '', 'sorting_foreign');
            if (is_array($damFiles) && count($damFiles) > 0) {
                $row[$this->thumbsCol] = implode(',', $damFiles['files']);
            }
		}
		// normal handling
		return parent::thumbCode($row,$table,$field);
	}	
}
