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
 * hook for class handleIncomingCommands in templavoila
 *
 * @author Daniel Agro <agro@gosign.de>
 *
 * @package		TYPO3
 * @subpackage	tx_gobackendlayout
 * @date		2012-10-24
 */
class tx_gobackendlayout_handleIncomingCommands {

	/**
	 * Hook, to handle the creation of a new go_stopcslide_pi1 element
	 *
	 * This hook is defined in templaVoila mod1/index.php
	 *
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date 2012-10-24
	 *
	 * @param String	$command: incoming command
	 * @param String	$redirectLocation: location to redirect after process
	 * @param Object	$self is the parent object; instance of class tx_templavoila_module1 which extends t3lib_SCbase
	 * @return void.
	 */
	function handleIncomingCommands_preProcess($command, &$redirectLocation, $self) {
		$return = FALSE;
		if ($command == 'createNewRecord') {
			$defVals = t3lib_div::_GP('defVals');
			$newRow = is_array ($defVals['tt_content']) ? $defVals['tt_content'] : array();
			if ($newRow['CType'] == 'go_stopcslide_pi1' && (!empty($newRow))) {
					// Create new record and open it for editing
				$destinationPointer = $self->apiObj->flexform_getPointerFromString(t3lib_div::_GP($command));
				$newUid = $self->apiObj->insertElement($destinationPointer, $newRow);
				$redirectLocation = $GLOBALS['BACK_PATH'] . t3lib_extMgm::extRelPath('templavoila')
					. 'mod1/index.php?' . $self->link_getParameters();

				$return = TRUE;
			}
		}

		return $return;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/hooks/class.tx_gobackendlayout_handleIncomingCommands.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/hooks/class.tx_gobackendlayout_handleIncomingCommands.php']);
}
?>