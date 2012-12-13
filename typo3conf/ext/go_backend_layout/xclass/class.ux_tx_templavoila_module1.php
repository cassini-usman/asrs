<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Daniel Agro (agro@gosign.de)
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
 * XCLASS for the page view to show the inherit notification if the content of a sheet is
 * inherited and empty
 *
 * @author		Daniel Agro <agro@gosign.de>
 * @date		2012-10-23
 * @package		TYPO3
 * @subpackage	tx_gobackendlayout
 */
class ux_tx_templavoila_module1 extends tx_templavoila_module1 {

	/**
	 * xClassed function of tx_templavoila_module1
	 *
	 * @param	array		$elementContentTreeArr: Array with the whole content element tree
	 * @param	string		$languageKey: typo3 language key
	 * @param	string		$sheet: active sheet
	 * @param	string		$calcPerms: Permition flag
	 * @return	string		rendered subelement
	 */
	function render_framework_subElements($elementContentTreeArr, $languageKey, $sheet, $calcPerms = 0) {
			// parent call to keep the original functionality after templavoila update
		$return = parent::render_framework_subElements(
			$elementContentTreeArr,
			$languageKey,
			$sheet,
			$calcPerms
		);

		if ($elementContentTreeArr['el']['table'] == 'pages') {
				// init some xclass class vars
			$this->xclassInit($elementContentTreeArr, $languageKey, $sheet, $calcPerms);
				// manipulate the output, to add the inheritance notification
			$return = $this->manipulateSheets($elementContentTreeArr, $sheet, $return);
		}

		return $return;
	}

	/**
	 * init function for the class
	 *
	 * @param	array		$elementContentTreeArr: Array with the whole content element tree
	 * @param	string		$languageKey: typo3 language key
	 * @param	string		$sheet: active sheet
	 * @param	string		$calcPerms: Permition flag
	 */
	function xclassInit($elementContentTreeArr, $languageKey, $sheet, $calcPerms = 0) {
			// Define l/v keys for current language:
		$langChildren = intval($elementContentTreeArr['ds_meta']['langChildren']);
		$langDisable = intval($elementContentTreeArr['ds_meta']['langDisable']);

		$this->lKey = $this->determineFlexLanguageKey($langDisable, $langChildren, $languageKey);
		$this->vKey = $this->determineFlexValueKey($langDisable, $langChildren, $languageKey);

		$this->pageID = $elementContentTreeArr['el']['uid'];
		$this->pid = $elementContentTreeArr['el']['pid'];

		$this->templavoilaScriptPath = $GLOBALS['BACK_PATH'] . t3lib_extMgm::extRelPath('templavoila') . 'mod1/index.php';
	}

	/**
	 * This functions manipulates a sheet which is inherited and empty
	 *
	 * @param	array		$elementContentTreeArr: Array with the whole content element tree
	 * @param	string		$sheet: active sheet
	 * @param	string		$parentReturnValue: return value of the origin function call
	 *
	 * @return	string		manipulated subelement string
	 */
	function manipulateSheets($elementContentTreeArr, $sheet, $parentReturnValue) {
			// traverse the sheets
		foreach ($elementContentTreeArr['previewData']['sheets'][$sheet] as $fieldID => $fieldConfig) {
				// check if the coloumn has configured an inheritance
			if ($this->checkInheritance($fieldConfig)) {
					// determine the noticifation string
				$inheritInfo = $this->getInheritInfo($fieldConfig, $fieldID);

					// determine the title and replace the title with itself plus our notification string
				$title = $GLOBALS['LANG']->sL($elementContentTreeArr['sub'][$sheet][$this->lKey][$fieldID][$this->vKey]['meta']['title'], 1);
				$parentReturnValue = preg_replace(
					'/(' . $title . '<\/td>\s*<\/tr>)(\s*<tr>)/U' . (mb_check_encoding($parentReturnValue, 'UTF-8') ? 'u' : ''),
					'\1' . $inheritInfo . '\2',
					$parentReturnValue
				);
			}
		}

		return $parentReturnValue;
	}

	/**
	 * This function checks if a manipulation is neccessary
	 *
	 * @param	array		$fieldConfig: Array with the sheet config
	 *
	 * @return	boolean		TRUE if manipulation is neccessary
	 */
	function checkInheritance($fieldConfig) {
			// check for templavoila configuration
		$return = is_array($fieldConfig['tx_templavoila']);
			// check if type is ce
		$return &= $fieldConfig['tx_templavoila']['eType'] == 'ce';
			// check for content slide call in the typoscript
		$return &= strpos($fieldConfig['tx_templavoila']['TypoScript'], 'tx_kbtvcontslide_pi1->main') !== FALSE;

		return $return;
	}

	/**
	 * This function determines the inherit info string
	 *
	 * @param	array		$fieldConfig: Array with the sheet config
	 * @param	string		$fieldID: name of the coloum
	 *
	 * @return	string	inherit info string
	 */
	function getInheritInfo($fieldConfig, $fieldID) {
		$inheritInfoWrap = array('<tr><td class="inheritInfo">', '</td></tr>');

		$returnArray = array();
			// notification headline
		$returnArray[] = array(
			'class' => 'notificationHeadline',
			'content' => $GLOBALS['LANG']->sL('LLL:EXT:go_backend_layout/locallang.xml:notificationHeadline'),
		);

			// check if we have content elements in the coloum and note the result to the BE user
		if (empty($fieldConfig['data'][$this->lKey][$this->vKey])) {
				// fetch the page which the displayed page is inheriting from
			$inheritanceRoot = $this->getInheritanceRoot($fieldID);
				// check if we found a inheritance root
			if ($inheritanceRoot != 0) {
					// determine the show string
				$inheritanceRootInfoString = $this->getInheritanceRootInfoString($inheritanceRoot);
					// notification
				$returnArray[] = array(
					'class' => 'notification',
					'content' => sprintf(
						$GLOBALS['LANG']->sL('LLL:EXT:go_backend_layout/locallang.xml:inherited'),
						$inheritanceRootInfoString
					),
				);
					// link to the page which the displayed page is inheriting from
				if ($GLOBALS['BE_USER']->isAdmin() || $GLOBALS['BE_USER']->isInWebMount($this->pageID)) {
					$returnArray[] = array(
						'class' => 'jumpLink',
						'content' => $GLOBALS['LANG']->sL('LLL:EXT:go_backend_layout/locallang.xml:jumpToInheritanceRoot'),
						'linkTarget' => $this->templavoilaScriptPath . '?id=' . $inheritanceRoot . '&updatePageTree=true',
					);
				}
					// create record link which create a stop content slide element
				if ($GLOBALS['BE_USER']->isAdmin() || tx_gobackendlayout_static::checkFieldAccess($fieldID, 'go_stopcslide_pi1', 0)) {
					$returnArray[] = array(
						'class' => 'stopSlide',
						'content' => $GLOBALS['LANG']->sL('LLL:EXT:go_backend_layout/locallang.xml:stopSlide'),
						'linkTarget' => $this->templavoilaScriptPath . '?id=' . $this->pageID
							. '&createNewRecord=pages:' . $this->pageID . ':sDEF:' . $this->lKey . ':' . $fieldID
							. ':' . $this->vKey . ':0&defVals[tt_content][CType]=go_stopcslide_pi1',
					);
				}
			} else {
					// nothing to inherit
				$returnArray[] = array(
					'class' => 'inheritedNothing',
					'content' => $GLOBALS['LANG']->sL('LLL:EXT:go_backend_layout/locallang.xml:inheritedNothing'),
				);
			}
		} else {
				// coloumn inherits its tt_content
			$returnArray[] = array(
				'class' => 'inherits',
				'content' => $GLOBALS['LANG']->sL('LLL:EXT:go_backend_layout/locallang.xml:inherits'),
			);
		}

			// build the html here
		$returnArrayFinal = array_map(
			function($value) {
				if (!empty($value['linkTarget'])) {
					$content = '<a href="' . $value['linkTarget'] . '">' . $value['content'] . '</a>';
				} else {
					$content = $value['content'];
				}

				return '<p class="' . $value['class'] . '">' . $content . '</p>';
			}, $returnArray
		);

		return $inheritInfoWrap[0] . implode('', $returnArrayFinal) . $inheritInfoWrap[1];
	}

	/**
	 * This function determines the inheritance root
	 *
	 * @param	string	$fieldID: templavoila field name
	 *
	 * @return	int		inheritance root uid
	 */
	function getInheritanceRoot($fieldID) {
		$pid = $this->pid;
		$return = 0;

			// go back in the rootline until we find content elements in the given templa voila field
			// or until we reach the root of the page tree
		while ($pid != 0) {
			$parentRow = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
				'uid, pid, tx_templavoila_flex',
				'pages',
				'uid = ' . $pid
			);
			if (!empty($parentRow['tx_templavoila_flex'])) {
				$flex = t3lib_div::xml2array($parentRow['tx_templavoila_flex']);
				if (!empty($flex['data']['sDEF'][$this->lKey][$fieldID][$this->vKey])) {
						// return the uid of the current page row
					$return = $parentRow['uid'];
					break;
				}
			}
			$pid = $parentRow['pid'];
		}

		return $return;
	}

	/**
	 * Determines the inheritance root info string
	 *
	 * @param	int		$pageID: page id to build the info string for
	 *
	 * @return	String	inheritance root info string
	 */
	function getInheritanceRootInfoString($pageID) {
		$pageRow = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
			'uid, pid, title',
			'pages',
			'uid = ' . $pageID
		);

		return '"' . $pageRow['title'] . ($GLOBALS['BE_USER']->isAdmin() ? ' (' . $pageID . ')' : '') . '"';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/class.ux_tx_templavoila_module1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/class.ux_tx_templavoila_module1.php']);
}

?>