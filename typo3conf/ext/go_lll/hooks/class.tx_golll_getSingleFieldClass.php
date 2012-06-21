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

/**
 * hook for t3lib_tceforms
 *
 * @author Daniel Agro <agro@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_golll
 */
class tx_golll_getSingleFieldClass {

	/**
	 * This function Applies the user sorting to the inline element
	 * The user sorting is given by the field 'tx_golll_sorting' in tt_content
	 * (hook function)
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date 2012-06-21
	 *
	 * @param	String	$table	Table we are working with
	 * @param	String	$field	Field name which is to handle
	 * @param	array	$row	tt_content row we are working with
	 * @param	array	$PA		TCA configuration for the field
	 */
	public function getSingleField_beforeRender($table, $field, $row, &$PA) {
		if ($row['CType'] === 'go_lll_piLabel') {
			if ($PA['fieldConf']['config']['form_type'] === 'inline') {
				$sorting = $this->getSorting($row['tx_golll_sorting']);
				$rows = $this->fetchRecords($PA, $sorting);

					// fetch the uids
				$idsArray = array();
				foreach ($rows as $row) {
					$idsArray[] = $row['uid'];
				}

					// returning our work by reference
				$PA['itemFormElValue'] = implode(',', $idsArray);
			}
		}
	}

	/**
	 * This function returns the sortBy String depend on the 'tx_golll_sorting' value
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date 2012-06-21
	 *
	 * @param	Int	$sortingValue	sorting value given by the tt_content fiel 'tx_golll_sorting'
	 *
	 * @return	String	sortBy String for the db-request
	 */
	public function getSorting($sortingValue) {
		switch ($sortingValue) {
			case 1:
				$sorting = 'tx_golll_langlabel, tx_golll_label';
				break;
			case 0:
			default:
				$sorting = 'tx_golll_label, tx_golll_langlabel';
				break;
		}

		return $sorting;
	}

	/**
	 * This Function fetches the records with the given sorting
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date 2012-06-21
	 *
	 * @param	array	$PA			TCA configuration for the field
	 * @param	String	$sorting	sortBy String for the db-request
	 *
	 * @return	Array	fetched rows
	 */
	public function fetchRecords($parentTCAconfig, $sorting) {
		$select = '*';
		$table = $parentTCAconfig['fieldConf']['config']['foreign_table'];
		$where = 'uid IN (' . $parentTCAconfig['itemFormElValue'] . ') ';
		$enableFields = t3lib_BEfunc::BEenableFields($table).t3lib_BEfunc::deleteClause($table);

		return $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select, $table, $where . $enableFields, '', $sorting, '', '');
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_getSingleFieldClass.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_getSingleFieldClass.php']);
}
?>