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
 * hook for class db_new_content_el in templavoila
 *
 * @author Daniel Agro <agro@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_golll
 */
class tx_golll_handleIncomingCommands {

	var $go_lllBElib;

	/**
	 * If Translation-Element for this plugin already exists -> return true so the command will be skipped
	 *
	 * This hook we use is defined in templaVoila mod1/index.php
	 *
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @author	Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-26
	 *
	 * @param String	$command should be createNewRecord
	 * @param int		redirectLocation doesn't matter to us
	 * @param Object	$this is an object of class tx_templavoila_module1 extends t3lib_SCbase
	 * @return void.
	 */
	function handleIncomingCommands_preProcess($command, &$redirectLocation, $self) {
		$this->go_lllBElib = t3lib_div::makeInstance('tx_golll_belib');
		if ($command == 'createNewRecord') {
			$defVals = t3lib_div::_GP('defVals');
			$newRow = is_array ($defVals['tt_content']) ? $defVals['tt_content'] : array();
			if ($newRow['CType'] == 'go_lll_piLabel' && (!empty($newRow))) {
					// now we know what CType we must handle
				$translatedCType = $newRow['tx_golll_ctype'];

				$cUID = $this->go_lllBElib->checkIfContentElementAlreadyExists($translatedCType);
				if (!$cUID) {
					$this->go_lllBElib->writeLabelsToDatabase($translatedCType);
				} else {
					$commandParameters = t3lib_div::_GP($command);
					$apiObj = t3lib_div::makeInstance('tx_templavoila_module1');
					$redirectLocation = $GLOBALS['BACK_PATH'].'alt_doc.php?edit[tt_content]['.$cUID.']=edit&returnUrl='.rawurlencode(t3lib_extMgm::extRelPath('templavoila').'mod1/index.php?'.$apiObj->link_getParameters());
					return TRUE;
				}
			}
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_handleIncomingCommands.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/hooks/class.tx_golll_handleIncomingCommands.php']);
}
?>