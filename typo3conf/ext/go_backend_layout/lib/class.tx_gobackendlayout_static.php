<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Caspar Stuebs <caspar@gosign.de>
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Backend library for the go_backend_layout extension.
 *
 * @author	Caspar Stuebs <caspar@gosign.de>
 * @author	Daniel Agro <agro@gosign.de>
 * @date	2012-04-26
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */
class tx_gobackendlayout_static {

	/**
	 * Add extension to the new content element wizard
	 * This function has to be called from ext_tables.php after the call of t3lib_extMgm::addPlugin()
	 *
	 * @param	string	$cType: name of the plugin to add (REQUIRED!)
	 * @param	array	$conf: configurations for the plugin to add
	 *
	 * @return	string	$errorMessage: reason why the plugin didn't get added to the list; empty if successful
	 */
	public static function addPluginToWizard($cType, array $conf = array()) {
		if (!$cType) {
			return 'no CType';
		}

		$cTypeArray = tx_gobackendlayout_static::getCTypeArrayFromTCA($cType);

		if (empty($cTypeArray)) {
			return 'CType "' . $cType . '" not found in TCA';
		}

		$defaultConf = array(
			'icon' => $cTypeArray[2],
			'title' => $cTypeArray[0],
			'description' => $cTypeArray[0] . '.description',
			'tt_content_defValues' => array(
				'CType' => $cType,
			),
		);

		$conf = t3lib_div::array_merge_recursive_overrule($defaultConf, $conf);

		/*
		* build default page ts
		*/
		$defaultPageTSconfig = array();
		$defaultPageTSconfig[] = 'templavoila.wizards.newContentElement.wizardItems.common.elements.' . $cType . ' {';
		foreach ($conf as $key => $value) {
			if (!is_array($value)) {
					$defaultPageTSconfig[] = "\t" . $key . ' = ' . $value;
			} else {
				$defaultPageTSconfig[] = "\t" . $key . ' {';
				foreach ($value as $k => $v) {
					if (strpos($v, "\n") === FALSE) {
						$defaultPageTSconfig[] = "\t\t" . $k . ' = ' . $v;
					} else {
						$defaultPageTSconfig[] = "\t\t" . $k . ' (' . "\n" . $v . "\n" . ')';
					}
				}
				$defaultPageTSconfig[] = "\t" . '}';
			}
		}
		$defaultPageTSconfig[] = '}';

		$defaultPageTSconfig[] = 'templavoila.wizards.newContentElement.wizardItems.common.show := addToList(' . $cType . ')';

		t3lib_extMgm::addPageTSConfig("\n" . implode("\n", $defaultPageTSconfig) . "\n");
	}

	/**
	 * Get the config item element of the given CType from TCA
	 *
	 * @param	string	$cType: The CType to get the config item array for
	 *
	 * @return	array	The config item array for the given CType
	 */
	public static function getCTypeArrayFromTCA($cType) {
		$returnArray = array();

		t3lib_div::loadTCA('tt_content');
		if (is_array($GLOBALS['TCA']['tt_content']['columns']) && is_array($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'])) {
			foreach ($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] as $itemArray) {
				if ($itemArray[1] == $cType) {
					$returnArray = $itemArray;
					$returnArray[2] = tx_gobackendlayout_static::getIcon($returnArray[2]);
					break;
				}
			}
		}

		return $returnArray;
	}

	/**
	 * Fetches the plugin icon
	 *
	 * @param	string	$filePath: The filepath from TCA
	 *
	 * @return	array	The first existing iconpath from $possibleIconFiles ('wizard.gif', 'wizard.png', 'wizard.jpg',
	 *					'icon.gif', 'icon.png', 'icon.jpg', '../ext_icon.gif') ang the go_backend_layout/ext_icon.gif
	 *					as fallback
	 */
	public static function getIcon($filePath) {
			// go_backend_layout/ext_icon.gif is the fallback
		$returnPath = t3lib_extMgm::extRelPath('go_backend_layout') . 'ext_icon.gif';
		$possibleIconFiles = array('wizard.gif', 'wizard.png', 'wizard.jpg', 'icon.gif', 'icon.png', 'icon.jpg', '../ext_icon.gif');
		foreach($possibleIconFiles as $checkFile) {
			$filePath = str_replace(basename($filePath), $checkFile, $filePath);
			if (file_exists(PATH_site . str_replace('../typo3conf', 'typo3conf', $filePath))) {
				$returnPath = $filePath;
				break;
			}
		}

		return $returnPath;
	}

	/**
	 * Checks, if the given CType is allowed to be added at given templavoila field
	 *
	 * @param	string	$fieldName: The templavoila field
	 * @param	string	$elementKey: The CType to check
	 *
	 * @return	boolean	TRUE, if access is allowed
	 */
	public static function checkFieldAccess($fieldName, $elementKey) {
		t3lib_div::loadTCA('tt_content');
		$access = $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
			'*',
			'tx_gobackendlayout_fieldrights',
			'fieldName=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($fieldName) . ' AND elementKey=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($elementKey) . ' AND deleted=0'
		);
		return $access;
	}

	/**
	 * Grants access for the given CType to the given templavoila field
	 *
	 * @param	string	$fieldName: The templavoila field
	 * @param	string	$elementKey: The CType
	 *
	 * @return	void
	 */
	public static function grantFieldAccess($fieldName, $elementKey) {
		if ($GLOBALS['BE_USER']->isAdmin() && !self::checkFieldAccess($fieldName, $elementKey)) {
			$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_gobackendlayout_fieldrights',
				array('fieldName' => $fieldName, 'elementKey' => $elementKey)
			);
		}
	}

	/**
	 * Revokes access for the given CType to the given templavoila field
	 *
	 * @param	string	$fieldName: The templavoila field
	 * @param	string	$elementKey: The CType
	 *
	 * @return	void
	 */
	public static function revokeFieldAccess($fieldName, $elementKey) {
		if ($GLOBALS['BE_USER']->isAdmin() && self::checkFieldAccess($fieldName, $elementKey)) {
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'tx_gobackendlayout_fieldrights',
				'fieldName=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($fieldName) . ' AND elementKey=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($elementKey),
				array('deleted' => 1)
			);
		}
	}
}

?>