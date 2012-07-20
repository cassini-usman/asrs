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
	 * The go_backend_layout extension config
	 *
	 * @var	array	The go_backend_layout extension config
	 */
	protected static $extensionConfig = array();

	/**
	 * Reads the extension config for the given extension from $GLOBALS array
	 *
	 * @param	string	$extensionKey: The extension key
	 *
	 * @return	array	The extension config
	 */
	public static function readExtensionConfig($extensionKey = 'go_backend_layout') {
		if (t3lib_extMgm::isLoaded($extensionKey)) {
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionKey])) {
				$extensionConfig = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionKey];
			} else {
				$extensionConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extensionKey]);
			}
		}

		return is_array($extensionConfig) ? $extensionConfig : array();
	}

	/**
	 * Gets the go_backend_layout extension config
	 * Takes it from $GLOBALS array if empty
	 *
	 * @return	array	The go_backend_layout extension config
	 */
	public static function getStaticExtensionConfig() {
		if (empty(self::$extensionConfig) || !is_array(self::$extensionConfig)) {
			$tempExtensionConfig = self::readExtensionConfig();

			self::$extensionConfig = (is_array($tempExtensionConfig) && !empty($tempExtensionConfig)) ? $tempExtensionConfig : array('disableFlexformRightsManagement' => FALSE);
		}

		return self::$extensionConfig;
	}

	/**
	 * Checks if the rights management for templavoila flexform elements is disabled
	 *
	 * @return	boolean	TRUE, if the rights management is disabled
	 */
	public static function flexformRightsManagementDisabled() {
		$extConf = self::getStaticExtensionConfig();

		return (boolean) $extConf['disableFlexformRightsManagement'];
	}

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
		$returnArray = tx_gobackendlayout_static::getColumnItemArrayFromTCA(array('table' => 'tt_content', 'column' => 'CType', 'value' => $cType));

		if (!empty($returnArray)) {
			$returnArray[2] = tx_gobackendlayout_static::getIcon($returnArray[2]);
		}

		return $returnArray;
	}

	/**
	 * Get a config item array from TCA, defined by table and column and value
	 *
	 * @param	array	$options: The options to find the requested item array
	 * @param	string	$options|table: The TCA table, default 'tt_content'
	 * @param	string	$options|column: The column of the table, default 'CType'
	 * @param	string	$options|value: The value of the item to find, default ''
	 *
	 * @return	array	An array of a config item (label, value, icon), emtpy if not found
	 */
	public static function getColumnItemArrayFromTCA($options) {
		$defaultOptions = array(
			'table' => 'tt_content',
			'column' => 'CType',
			'value' => '',
		);

		$options = t3lib_div::array_merge_recursive_overrule($defaultOptions, $options);

		t3lib_div::loadTCA($options['table']);

			// return an empty array, if table or coulmn not found or items array is empty
		if (!is_array($GLOBALS['TCA'][$options['table']]) ||
				!is_array($GLOBALS['TCA'][$options['table']]['columns'][$options['column']]) ||
				!is_array($GLOBALS['TCA'][$options['table']]['columns'][$options['column']]['config']['items']) ||
				empty($GLOBALS['TCA'][$options['table']]['columns'][$options['column']]['config']['items'])) {
			return array();
		}

		$returnArray = array();
		foreach ($GLOBALS['TCA'][$options['table']]['columns'][$options['column']]['config']['items'] as $itemArray) {
			if ($itemArray[1] == $options['value']) {
				$returnArray = $itemArray;
				break;
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
		if ($filePath) {
			$filePath = dirname($filePath) . '/';

			$checkFilePath = t3lib_div::resolveBackPath(PATH_typo3 . $filePath);
			$checkFilePath = t3lib_div::getFileAbsFileName($checkFilePath);

			foreach ($possibleIconFiles as $checkFile) {
				if (file_exists($checkFilePath . $checkFile)) {
					$returnPath = $filePath . $checkFile;
					break;
				}
			}
		}

		return $returnPath;
	}

	/**
	 * This function adds the js to the backend
	 * working directory is "htdocs/typo3"
	 *
	 * @author:	Daniel Agro <agro@gosign.de>
	 * @date:	2012-06-04
	 *
	 * @param:	string	$file: file to include
	 * @param:	string	$extName: extension name where the file is to find
	 * @param	array	$conf: configures the options
	 *
	 * @return	void/string		ErrorMessage if file does not exist
	 */
	public static function addJavaScriptFile($file, $extName = 'go_backend_layout', array $conf = array()) {
		if (!$file) {
			return 'no file';
		}

		if(!file_exists(t3lib_extMgm::extPath($extName) . $file)) {
			return 'file ' . $file . 'does not exist\n working directory is ' . getcwd();
		}

		$defaultConf = array(
			'type' => 'text/javascript',
			'compress' => FALSE,
			'forceOnTop' => FALSE,
			'allWrap' => '',
		);

		$conf = t3lib_div::array_merge_recursive_overrule($defaultConf, $conf);

		$filePath = $GLOBALS['BACK_PATH'] . t3lib_extMgm::extRelPath($extName) . $file;
		$pageRenderer = $GLOBALS['TBE_TEMPLATE']->getPageRenderer();
		$pageRenderer->addJsFile($filePath, $conf['type'], $conf['compress'], $conf['forceOnTop'], $conf['allWrap']);
	}

	/**
	 * This function adds the css to the backend
	 * working directory is "htdocs/typo3"
	 *
	 * @author:	Daniel Agro <agro@gosign.de>
	 * @date:	2012-06-04
	 *
	 * @param:	string	$file: file to include
	 * @param:	string	$extName: extension name where the file is to find
	 * @param	array	$conf: configures the options
	 *
	 * @return	void/string		ErrorMessage if nof file given or file does not exist
	 */

	public static function addStyleSheetFile($file, $extName = 'go_backend_layout', array $conf = array()) {
		if (!$file) {
			return 'no file';
		}

		if(!file_exists(t3lib_extMgm::extPath($extName) . $file)) {
			return 'file ' . $file . 'does not exist\n working directory is ' . getcwd();
		}

		$defaultConf = array(
			'rel' => 'stylesheet',
			'media' => 'all',
			'compress' => FALSE,
			'forceOnTop' => FALSE,
			'allWrap' => '',
		);

		$conf = t3lib_div::array_merge_recursive_overrule($defaultConf, $conf);

		$filePath = $GLOBALS['BACK_PATH'] . t3lib_extMgm::extRelPath($extName) . $file;
		$pageRenderer = $GLOBALS['TBE_TEMPLATE']->getPageRenderer();
		$pageRenderer->addCssFile($filePath, $conf['rel'], $conf['media'], $conf['title'], $conf['compress'], $conf['allWrap']);
	}

	/**
	 * Checks, if the given CType is allowed to be added at given templavoila field
	 *
	 * @param	string	$fieldName: The templavoila field
	 * @param	string	$elementKey: The CType to check
	 * @param	string	$tvTemplateObject: The value for the field 'tx_templavoila_to'
	 *
	 * @return	BOOLEAN	field access
	 */
	public static function checkFieldAccess($fieldName, $elementKey, $tvTemplateObject) {
		if (self::flexformRightsManagementDisabled()) {
			return TRUE;
		}

		$accessRow = self::getAccessRow($fieldName, $elementKey, $tvTemplateObject);
		$return = $accessRow['access'] === 'true' ? TRUE : FALSE;

		if (!$GLOBALS['BE_USER']->isAdmin()) {
			$return = $return && $GLOBALS['BE_USER']->checkAuthMode('tt_content', 'CType', $elementKey, 'explicitAllow');
		}

		return $return;
	}

	/**
	 * Fetches the row for this rule
	 *
	 * @param	string	$fieldName: The templavoila field
	 * @param	string	$elementKey: The CType to check
	 * @param	string	$tvTemplateObject: The value for the field 'tx_templavoila_to'
	 *
	 * @return	array	the selected row
	 */
	public static function getAccessRow($fieldName, $elementKey, $tvTemplateObject) {
			// skip if no fieldName or elementKey given
		if (!$fieldName || !$elementKey || self::flexformRightsManagementDisabled()) {
			return array();
		}
		$tvTemplateObject = $tvTemplateObject ? (int) $tvTemplateObject : 0;

		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
			'*',
			'tx_gobackendlayout_fieldrights',
			'fieldName = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($fieldName, 'tx_gobackendlayout_fieldrights') .
				' AND elementKey = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($elementKey, 'tx_gobackendlayout_fieldrights') .
				' AND templateObject = ' . $tvTemplateObject
		);

		return $row;
	}

	/**
	 * Grants access for the given CType to the given templavoila field
	 *
	 * @param	string	$fieldName: The templavoila field
	 * @param	string	$elementKey: The CType
	 * @param	string	$tvTemplateObject: The value for the field 'tx_templavoila_to'
	 *
	 * @return	void
	 */
	public static function grantFieldAccess($fieldName, $elementKey, $tvTemplateObject) {
		if ($GLOBALS['BE_USER']->isAdmin() && !self::flexformRightsManagementDisabled()) {
			$accessRow = self::getAccessRow($fieldName, $elementKey, $tvTemplateObject);
				// if there is no rule for this field and this element -> insert a new rule
			if (!$accessRow) {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery(
					'tx_gobackendlayout_fieldrights',
					array('fieldName' => $fieldName, 'elementKey' => $elementKey, 'templateObject' => (int) $tvTemplateObject, 'access' => 'true')
				);
			} else { // else -> update the existing rule
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_gobackendlayout_fieldrights',
					'uid = ' . $accessRow['uid'],
					array('access' => 'true')
				);
			}
		}
	}

	/**
	 * Revokes access for the given CType to the given templavoila field
	 *
	 * @param	string	$fieldName: The templavoila field
	 * @param	string	$elementKey: The CType
	 * @param	string	$tvTemplateObject: The value for the field 'tx_templavoila_to'
	 *
	 * @return	void
	 */
	public static function revokeFieldAccess($fieldName, $elementKey, $tvTemplateObject) {
		if ($GLOBALS['BE_USER']->isAdmin() && !self::flexformRightsManagementDisabled()) {
			$accessRow = self::getAccessRow($fieldName, $elementKey, $tvTemplateObject);
			if ($accessRow) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_gobackendlayout_fieldrights',
					'uid = ' . $accessRow['uid'],
					array('access' => 'false')
				);
			}
		}
	}

	/**
	 * This function checks in which temaplavoila field we are
	 *
	 * @author:	Daniel Agro <agro@gosign.de>
	 * @date:	2012-06-14
	 *
	 * @param	String	$dataRow the contentelement to check for
	 *
	 * @return	String	the fieldname
	 */
	public static function getFieldName($dataRow) {
		$return = '';
			// fetch the flex xml
		$pageRow = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
			'tx_templavoila_flex',
			'pages',
			'uid = ' . $dataRow['pid']
		);
			// check which uid we have to search in the flexArray
		$uidField = $dataRow['sys_language_uid'] && $dataRow['l18n_parent'] ? 'l18n_parent' : 'uid';
		$localizedUID = $dataRow[$uidField];

		$return = self::recursiveFlexSearch($pageRow, $localizedUID);

		return $return;
	}

	/**
	 * This function searches recursive in contentdevider for the CE with the given UID
	 *
	 * @author:	Daniel Agro <agro@gosign.de>
	 * @date:	2012-06-14
	 *
	 * @param	Array	$row 			in first call: pageRow to check
	 *									in recursive calls: contentRow to check
	 * @param	String	$localizedUID 	The UID to search for
	 *
	 * @return	String	templavoila fieldname
	 */
	public static function recursiveFlexSearch(array $row, $localizedUID) {
		if (empty($row) || !t3lib_div::testInt($localizedUID) || $localizedUID <= 0) {
			return '';
		}

		$flexArray = t3lib_div::xml2array($row['tx_templavoila_flex']);

		if (!is_array($flexArray['data']['sDEF']['lDEF'])) {
			return '';
		}

		$return = '';

			// check all templavoila fields of this templavoila CE
		foreach ($flexArray['data']['sDEF']['lDEF'] as $fieldName => $vDEF) {
			$uidsFromFlexField = t3lib_div::trimExplode(',', (reset($vDEF)));
				// return $fieldName if $localizedUID found
			if (in_array($localizedUID, $uidsFromFlexField)) {
				$return = $fieldName;
				break;
			}

				// fetch all templavoila CE if uid is in $uidList
			$where = 'uid IN (' . reset($vDEF) . ') AND CType = "templavoila_pi1" ' . t3lib_BEfunc::BEenableFields('tt_content') . t3lib_BEfunc::deleteClause('tt_content');
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid, tx_templavoila_flex', 'tt_content', $where);

			while ($newRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					// skip if no flex xml
				if (!$newRow['tx_templavoila_flex']) {
					continue;
				}

					// recursive call
				$tempFieldName = self::recursiveFlexSearch($newRow, $localizedUID);
				if ($tempFieldName) {
					$return = $tempFieldName;
					break 2;
				}
			}
		}

		return $return;
	}
}
?>