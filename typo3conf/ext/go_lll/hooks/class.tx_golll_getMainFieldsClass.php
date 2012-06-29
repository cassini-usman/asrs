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
class tx_golll_getMainFieldsClass {

	var $go_lllBElib;

	/**
	 * Hook into class.t3lib_tceforms.php
	 * Call writeLabelsToDatabase() if CType "go_lll_piLabel" is edited.
	 *
	 * Find lost label entries and relate them to our (new) "translation element".
	 *
	 * @param string	$table	The DB Table name, which is now edited.
	 * @param array 	$row  	The DB row
	 * @param object	$self	Of class t3lib_TCEforms
	 * @return void
	 *
	 * @date 2012-02-17
	 * @author Marius Stuebs <marius@gosign.de>
	 * @author Daniel Agro <agro@gosign.de>
	 */
	function getMainFields_preProcess($table, $row, $self) {
		$this->go_lllBElib = t3lib_div::makeInstance('tx_golll_belib');
		if ($table === 'tt_content') {
			if ($row['CType'] === 'go_lll_piLabel') {
				$translatedCType = $row['tx_golll_ctype'];

					// how many Labels are missing? Write and count!
				$count = $this->go_lllBElib->writeLabelsToDatabase($translatedCType);

					// check if there are existing labels directing to the wrong record
				$count2 = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('COUNT(uid) as count', 'tx_golll_translation', 'tx_golll_ctype = "' . $translatedCType . '" AND parentElement != ' . $row['uid']);
				if ($count2['count'] > 0) {
						//there are existing labels directing to the wrong record? Count them and correct them.
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_golll_translation', 'tx_golll_ctype = "' . $row['tx_golll_ctype'] . '"',  array( 'parentElement' => $row['uid']));
					$count += $count2['count'];
				}

					// check if there deleted elements
				$deletedElements = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('COUNT(uid) as count', 'tx_golll_translation', 'tx_golll_ctype = "' . $translatedCType . '" AND deleted = 1');
				if ($deletedElements['count'] > 0) {
						//there are existing labels directing to the wrong record? Count them and correct them.
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_golll_translation', 'tx_golll_ctype = "' . $row['tx_golll_ctype'] . '"',  array( 'deleted' => '0'));
					$count += $deletedElements['count'];
				}

				if ($count > 0 and !$GLOBALS['tx_golll_redirectcount']) {
					//$apiObj = t3lib_div::makeInstance('tx_templavoila_module1');
					$redirectLocation = $GLOBALS['BACK_PATH'].'alt_doc.php?edit[tt_content]['.$row['uid'].']=edit&returnUrl='.rawurlencode(t3lib_extMgm::extRelPath('templavoila').'mod1/index.php?'); //.$apiObj->link_getParameters());
					$GLOBALS['tx_golll_redirectcount'] = 1;
					t3lib_utility_http::redirect($redirectLocation);
				}

				return TRUE;
			}
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_getMainFieldsClass.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_getMainFieldsClass.php']);
}
?>