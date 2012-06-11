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

require_once(PATH_typo3 . 'interfaces/interface.cms_newcontentelementwizarditemshook.php');

/**
 * New content elements wizard hook for templavoila
 *
 * @author		Caspar Stuebs <caspar@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */

class tx_gobackendlayout_manipulateWizardItems implements cms_newContentElementWizardsHook {

	/**
	 * This hook ...
	 *
	 * @author	Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	array	$wizardItems: The wizard item as reference
	 * @param	array	$parentObject: The templavoila wizard object as reference
	 *
	 * @return	void
	 */
	public function manipulateWizardItems(&$wizardItems, &$parentObject) {
		if ($GLOBALS['BE_USER']->isAdmin()) {
			$this->createWizardItemsAdminFunctions($wizardItems, $parentObject);
		} else {
			$this->removeWizardItems($wizardItems, $parentObject);
		}
	}

	/**
	 *
	 * @author	Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	array	$wizardItems: The wizard item as reference
	 * @param	array	$parentObject: The templavoila wizard object as reference
	 *
	 * @return	void
	 */
	protected function createWizardItemsAdminFunctions(&$wizardItems, &$parentObject) {
		$fieldName = $this->getFieldName($parentObject->parentRecord);

		$hiddenInfos = '';
		$hiddenInfosWrap = array('<input type="hidden" name="', '" value="', '" />');
		$hiddenInfosContainerWrap = array('<div class="hiddenInfos">', '</div>');
		$itemAlreadyParsed = array();
		foreach ($wizardItems as $item) {
			$cType = $item['tt_content_defValues']['CType'];
			if ($cType && !in_array($itemAlreadyParsed, $cType)) {
				$hiddenInfos .= $this->buildHiddenInfo($cType, $item['tt_content_defValues']['tx_templavoila_to'], $fieldName);
				$itemAlreadyParsed[] = $cType;
			}
		}

		if(!$this->isLabelPage($parentObject->id)){
			$hiddenFieldNameInfo = '<input type="hidden" class="fieldName" value="' . $fieldName . '" />';
			$parentObject->content .= $hiddenInfosContainerWrap[0] . $hiddenFieldNameInfo . $hiddenInfos . $hiddenInfosContainerWrap[1];
			$checkBoxToAdd = '<td valign="top"><input type="checkbox" class="fieldrightsCheckbox" value="" /></td>';
		}

		$parentObject->elementWrapper['wizard'] = array('<tr class="row">', $checkBoxToAdd . $parentObject->elementWrapper['wizard'][1]);
		$parentObject->elementWrapperForTabs['wizard'] = $parentObject->elementWrapper['wizard'];
	}

	/**
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 *
	 * @param	string	$item: item to built hidden info for
	 *
	 * @return	hidden info
	 */
	protected function buildHiddenInfo($cType, $tvTemplateObject, $fieldName) {
		$hiddenInfo = '';
		$hiddenInfosWrap = array('<input type="hidden" name="tvTemplateObject" value="', '" ', '/>');
		$hiddenInfosContainerWrap = array('<div class="', '">', '</div>');
		$tvTemplateObject = $tvTemplateObject ? (int) $tvTemplateObject : 0;

		$checkInformation = tx_gobackendlayout_static::checkFieldAccess($fieldName, $cType, $tvTemplateObject) ? 'checked="1"' : '';
		$hiddenInfo .= $hiddenInfosWrap[0] . $tvTemplateObject . $hiddenInfosWrap[1] . $checkInformation . $hiddenInfosWrap[2];
		$hiddenInfo = $hiddenInfosContainerWrap[0] . $cType . '_' . $tvTemplateObject . $hiddenInfosContainerWrap[1] . $hiddenInfo . $hiddenInfosContainerWrap[2];

		return $hiddenInfo;
	}

	/**
	 *
	 * @author	Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	array	$wizardItems: The wizard item as reference
	 * @param	array	$parentObject: The templavoila wizard object as reference
	 *
	 * @return	void
	 */
	protected function removeWizardItems(&$wizardItems, &$parentObject) {
		$fieldName = $this->getFieldName($parentObject->parentRecord);

		$currentSection = '';
		$currentSectionItemCount = 0;
		foreach ($wizardItems as $key => $item) {
			if ($item['header']) {
				if ($currentSection && !$currentSectionItemCount) {
					unset($wizardItems[$currentSection]);
				}
				$currentSection = $key;
				$currentSectionItemCount = 0;
			} elseif ($item['tt_content_defValues']['CType']) {
				$tvTemplateObject = $item['tt_content_defValues']['tx_templavoila_to'] ? (int) $item['tt_content_defValues']['tx_templavoila_to'] : 0;
				if (!tx_gobackendlayout_static::checkFieldAccess($fieldName, $item['tt_content_defValues']['CType'], $tvTemplateObject)) {
					unset($wizardItems[$key]);
				} else {
					$currentSectionItemCount++;
				}
			} else {
				$currentSectionItemCount++;
			}
		}
		if ($currentSection && !$currentSectionItemCount) {
			unset($wizardItems[$currentSection]);
		}
	}

	/**
	 * Get the name of the field where the Element will be paste
	 *
	 * @author	Mansoor Ahmad <mansoor@gosign.de>
	 *
	 * @param	string	$parentRecord: The templavoila flexform info
	 *
	 * @return	string	Name of the Field
	 */
	protected function getFieldName($parentRecord) {
		$parentRecord = explode(':', $parentRecord);
		return $parentRecord[4];
	}

	/**
	 * This functions checks if we are on a label page (go_lll)
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 *
	 * @date: 2012-05-09
	 *
	 * @return	boolean		TRUE if we are on a label page
	 */
	protected function isLabelPage($pageID) {
		$return = ($pageID == $this->getTranslationPageUID()) ? TRUE : FALSE;
		return $return;
	}

	/**
	 * @author Marius Stuebs <marius@gosign.de>
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @date: 2012-02-16
	 *
	 * This function returns the UID of the translation page
	 *
	*/
	function getTranslationPageUID () {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'pages', 'module="go_lll" and deleted="0"', '', '', '1');
		if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			return $row['uid'];
		}
	}
}

?>