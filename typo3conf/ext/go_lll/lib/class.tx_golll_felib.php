<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Caspar Stuebs <caspar@gosign.de>
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
 * Frontend libary for the 'go_lll' extension.
 *
 * @author	Caspar Stuebs <caspar@gosign.de>
 * @package	TYPO3
 * @subpackage	tx_golll
 */
class tx_golll_felib implements t3lib_Singleton {
	/*
	 * Class constructor.
	 */
	public function __construct() {
		if (!isset($GLOBALS['go_lll']) || !is_array($GLOBALS['go_lll'])) {
			$GLOBALS['go_lll'] = array();
		}
	}

	/**
	 * Load the go_lll language labels from the database and override the $plugin->LOCAL_LANG array from the given plugin
	 *
	 * @param	object	$plugin: a reference to the related cObj
	 *
	 * @return	void
	 */
	public function pi_loadLL(&$plugin) {
		if (!$plugin->GO_LLL_loaded) {
			if (!empty($GLOBALS['go_lll'][$plugin->prefixId])) {
				$tempLOCAL_LANG = $GLOBALS['go_lll'][$plugin->prefixId];
			} else {
				$enableFields = $plugin->cObj->enableFields('tx_golll_translation');

				$cTypeForDb = $this->getCTypeForDb($plugin);

					// set the values for the given language ($plugin->LLkey)
				$tempLOCAL_LANG = array(
					$plugin->LLkey => $this->getLabelsFromDB($cTypeForDb, $plugin->LLkey, $enableFields)
				);

					//set the values for default language
				if ($plugin->LLkey !== 'default') {
					$tempLOCAL_LANG['default'] = $this->getLabelsFromDB($cTypeForDb, 'default', $enableFields);
				}

				$GLOBALS['go_lll'][$plugin->prefixId] = $tempLOCAL_LANG;
			}

			foreach ($tempLOCAL_LANG as $LLkey => $LLvals) { // merge the loaded languages in $plugin->LOCAL_LANG
				if (!is_array($plugin->LOCAL_LANG[$LLkey])) {
					$plugin->LOCAL_LANG[$LLkey] = array();
				}
				$plugin->LOCAL_LANG[$LLkey] = array_merge($plugin->LOCAL_LANG[$LLkey], $LLvals);
			}

			$plugin->GO_LLL_loaded = 1;
		}
	}

	/**
	 * Do a database query and return the found labels
	 *
	 * @param	string	$cType: the cType to get the labels for
	 * @param	string	$llKey: the language key (e.g. 'de' or 'default') to get the labels for
	 * @param	string	$enableFields: the enable fields string to pass to the db
	 *
	 * @return	array	An array with label => value pairs
	 */
	protected function getLabelsFromDB($cType, $llKey, $enableFields) {
		$returnLLarray = array();

		$query = array(
			'select' => 'tx_golll_value, tx_golll_label',
			'from' => 'tx_golll_translation',
			'where' => 'tx_golll_ctype = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($cType, 'tx_golll_translation') .
						' AND tx_golll_langlabel = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($llKey, 'tx_golll_translation') .
						' AND tx_golll_value <> \'\'' .
						$enableFields
		);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($query['select'], $query['from'], $query['where'], '', '', '');

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$returnLLarray[$row['tx_golll_label']] = $row['tx_golll_value'];
		}

		return $returnLLarray;
	}

	/**
	 * Do a database query and return the found labels
	 *
	 * @param	object	$plugin: a reference to the related cObj
	 *
	 * @return	string	An array with label => value pairs
	 */
	protected function getCTypeForDb(&$plugin) {
		$cTypeForDb = '';

		if (is_array($plugin->cObj->data['CType'])) {
			$cTypeForDb = ($plugin->cObj->data['CType'] === 'list') ? $plugin->cObj->data['list_type'] : $plugin->cObj->data['CType'];
		}

		if (!$cTypeForDb) {
			$classPrefix = t3lib_extMgm::getCN($plugin->extKey);
			$cTypeForDb = str_replace($classPrefix, $plugin->extKey, $plugin->prefixId);
		}
		return $cTypeForDb;
	}
}

?>