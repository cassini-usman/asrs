<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011   Daniel Agro <agro@gosign.de>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

/**
 * lib with userfuncs for TS
 *
 * @author Marius Stuebs <marius@gosign.de>
 * @author Daniel Agro <agro@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_golll
 */
class tx_golll_belib {
	/**
	 * std-ignore
	 */
	var $ignoreListStandard = 'header,text,textpic,image,bullets,table,splash,multimedia,script,media,search,shortcut,div,html,list,menu,uploads,login,mailform,templavoila_pi1,dlstats_pi1,1,go_stopcslide_pi1';

	/**
	 * customized ignore
	 */
	var $ignoreListCustomized = 'go_lll_piLabel,queo_speedup_pi1';

	/**
	 * This function inits the class variable $go_lllBElib with an instance
	 * of the go_lll lib
	 *
	 * @date 2012-06-21
	 *
	 * @author Daniel Agro <agro@gosign.de>
	 */
	public static function init() {
		return t3lib_div::makeInstance('tx_golll_belib');
	}

	/**
	 * Generate a list of CTypes that are allowed for Translation
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date 2012-06-19
	 *
	 * @param	array	$type type which has to be fetched (for example: 'cType', 'list_type')
	 * @return	Array	Item with given type which are allowed to make a label element for
	 */
	function getItemArrayByType($type) {
		if(!is_array($GLOBALS['TCA']['tt_content']['columns'][$type]['config']['items'])) {
			return array();
		}
		$myCType = 'go_lll_piLabel';
		$ignoreArray = explode(',', $this->ignoreListStandard);
		$ignoreArray = array_merge($ignoreArray, explode(',', $this->ignoreListCustomized));

		$itemsArray = array();
		foreach ($GLOBALS['TCA']['tt_content']['columns'][$type]['config']['items'] as $item) {
			if (!$item[1] || in_array($item[1], $ignoreArray) || $item[1] === '--div--' || !$this->hasLocallang($item[1])) {
				continue;
			}

			$wItem = array();
			$wItem['icon'] = tx_gobackendlayout_static::getIcon('');
			$wItem['title'] = 'Bezeichner-Element für ' . $GLOBALS['LANG']->sL($item[0]);
			$wItem['description'] = '';
			$wItem['tt_content_defValues']['tx_golll_ctype'] = $item[1];
			$wItem['tt_content_defValues']['CType'] = $myCType;
			$wItem['params'] = '&defVals[tt_content][CType]='.$myCType;
			$wItem['params'] .= '&defVals[tt_content][header]='.urlencode($this->computeHeaderValue($GLOBALS['LANG']->sL($item[0])));
			$wItem['params'] .= '&defVals[tt_content][tx_golll_ctype]='.$wItem['tt_content_defValues']['tx_golll_ctype'];

			$itemsArray[$item[1]] = $wItem;
		}

		return $itemsArray;
	}

	/**
	 * Generate a list of cType and list_types element that are allowed for Translation
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date 2012-06-19
	 *
	 * @return	Array	fetched wizarditems which are allowed to make a label element for
	 */
	function getWizardItems() {
		$returnArray = array_merge($this->getItemArrayByType('CType'), $this->getItemArrayByType('list_type'));

		return $returnArray;
	}

	/**
	 * Checks if there a translation CE already exists for this plugin
	 *
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @author	Marius Stuebs <marius@gosign.de>
	 * @date 2012-02-01
	 *
	 * @param String	$piName	name of the plugin we want to check for
	 *
	 * @return bool		uid if there already exists a translation CE for this plugin // FALSE it it doesn´t
	 */
	function checkIfContentElementAlreadyExists($piName) {
		$select = 'uid';
		$table = 'tt_content';
		$where = 'CType="go_lll_piLabel" AND tx_golll_ctype="' . $piName . '"';
		$enableFields = t3lib_BEfunc::BEenableFields($table).t3lib_BEfunc::deleteClause($table);
		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow($select, $table, $where . $enableFields );

		return $row['uid'];
	}

	/**
	 * Define what the plugin's name should be.
	 * Until now: Just the ctype that is displayed.
	 *
	 * @author	Marius Stuebs <marius@gosign.de>
	 * @date 2012-03-27
	 *
	 * @param String	$piName	ctype of the plugin we want to check for
	 *
	 * @return String	name of the plugin we want to check for
	 */
	function computeHeaderValue($piName) {
		return $piName;
	}


	/**
	 * determine existing language marker from plugin locallang.xml
	 *
	 * This function reads the locallang.xml of a given plugin
	 * and returns an array of all the used language markers.
	 *
	 * @date 2011-01-11
	 * @author Marius Stuebs <marius@gosign.de>
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param String $filename filename including path to the plugin locallang.xml
	 *
	 * @return Array List of available language markers
	 */
	function determineExistingXMLMarker($filename) {
		$contents = file_get_contents($filename);
		$markerArray = array();
		$count = preg_match_all('/label\s+index=(\'|")(.+)(\1)/U', $contents, $markerArray);
		$matches = array_unique($markerArray[2]);

		return $matches;
	}

	/**
	 * determine existing language marker from database
	 *
	 * This function reads the database entries of a given plugin
	 * and returns an array of all existing language markers.
	 *
	 * @date 2011-02-01
	 * @author Marius Stuebs <marius@gosign.de>
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param String $piName Name of the Plugin
	 *
	 * @return Array List of available language markers in format de_languagelabel
	 */
	function determineExistingDBMarker($piName) {
		$select = 'CONCAT(tx_golll_langlabel, \'_\', tx_golll_label)';
		$table = 'tx_golll_translation';
		$where = 'tx_golll_ctype="' . $piName . '"';
		$enableFields = t3lib_BEfunc::BEenableFields($table).t3lib_BEfunc::deleteClause($table);

		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select, $table, $where . $enableFields);

		return $rows;
	}
	/**
	 * Create database rows.
	 * for translation of the given CType
	 * Labels taken from locallang.xml and written to database.
	 * (is called each time our plugin is displayed in edit-view.)
	 *
	 * @date 2011-02-01
	 * @author	Marius Stuebs <marius@gosign.de>
	 * @author	Daniel Agro <agro@gosign.de>
	 *
	 * @param String $translatedCType CType to handel
	 *
	 */
	function writeLabelsToDatabase($translatedCType) {
		$filename = $this->getLocallangFilename($translatedCType);
		$markerArrayXML = $this->determineExistingXMLMarker($filename);
		$markerArrayDB = array(); //$this->determineExistingDBMarker($translatedCType);

			//only INSERT labels that are in the xml but not in the database
		$markerArray = array_diff($markerArrayXML, $markerArrayDB);

		$lang = t3lib_div::makeInstance('language');
			//as defined here: typo3/sysext/lang/lang.php line 464

			// get the t3-language of all installed languages
		$goLanguageLibary = t3lib_div::makeInstance('tx_golanguage');
		$sysLanguageUids = $goLanguageLibary->getSysLanguageUids();
		$availableLanguages = array();
		foreach ($sysLanguageUids as $languageUid) {
			$availableLanguages[] = $goLanguageLibary->getLanguageT3($languageUid);
		}

		// $availableLanguages = array_keys($GLOBALS['LOCAL_LANG']);
		$parsedLanguages = array();
		$pid = $this->getTranslationPageUID();
		$parentElement = $this->checkIfContentElementAlreadyExists($translatedCType);
		$count = 0;
		foreach($availableLanguages as $myLanguage) {
			$lang->lang = 'default';
			$lang->init($myLanguage);
			if (in_array($lang->lang, $parsedLanguages)) {
				continue;
			}
			$parsedLanguages[] = $lang->lang;
			$languageMarkersForThePlugin = $lang->includeLLFile($filename, FALSE, FALSE);

			foreach($markerArray as $singleMarker) {

				$fields = array(
					'tstamp' => time(),
					'crdate' => time(),
					'cruser_id' => $GLOBALS['BE_USER']->user['uid'],
					'pid' => $pid,
					'parentElement' => $parentElement,
					'tx_golll_ctype' => $translatedCType,
					'tx_golll_label' => utf8_encode($singleMarker),
					'tx_golll_value' => $languageMarkersForThePlugin[$lang->lang][$singleMarker],
					'tx_golll_langlabel' => $lang->lang // can be 'de', 'default', 'tr', 'es' or whatever exactly in the language file is listed.
				);
					// INSERT only works if the combination of (tx_golll_ctype, tx_golll_label, tx_golll_langlabel) is UNIQUE and NOT EXISTING
				$success = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_golll_translation', $fields);
				if ($success) {
					$count++;
				}
			}
		}

		return $count;
	}


	/**
	 * This function the filepath to the locallang.xml for the given cType
	 *
	 * @author 	Daniel Agro <agro@gosign.de>
	 * @date 	2012-05-10
	 *
	 * @param	$cType: String which contains the extension- and the pluginname
	 *
	 * @return	String Filepath to the locallang for the given plugin
	 */
	function getLocallangFilename($cType) {
		if ($cType === 'indexed_search') {
			$piName = 'pi';
			$extName = $cType;
		} else {
			$lastUnderscore = strrpos($cType, '_');
			$piName = substr($cType, $lastUnderscore + 1);
			$extName = substr($cType, 0, $lastUnderscore);
		}

		if (!t3lib_extMgm::isLoaded($extName)) {
			return FALSE;
		}

		$filename = t3lib_extMgm::extPath($extName) . $piName . '/locallang.xml';

		return $filename;
	}

	/**
	 * This function checks if the plugin for the given cType
	 * contains a locallang.xml file
	 *
	 * @author 	Daniel Agro <agro@gosign.de>
	 * @date 	2012-05-10
	 *
	 * @param	$cType: String which contains the extension- and the pluginname
	 *
	 * @return	Boolean 	True if plugin contains a locallang.xml
	 */
	function hasLocallang($cType) {
		$filename = $this->getLocallangFilename($cType);
		$return = FALSE;

		if(file_exists($filename)) {
			$return = TRUE;
		}

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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/lib/class.tx_golll_belib.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/lib/class.tx_golll_belib.php']);
}


?>