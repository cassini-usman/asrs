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

require_once(PATH_typo3 . 'interfaces/interface.cms_newcontentelementwizarditemshook.php');

/**
 * lib with userfuncs for TS
 *
 * @author Marius Stuebs <marius@gosign.de>
 * @author Daniel Agro <agro@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_golll
 */
class tx_golll_userfuncs implements cms_newContentElementWizardsHook {
	/**
	 * std-ignore
	 */
	var $ignoreListStandard = 'header,text,textpic,image,bullets,table,splash,multimedia,script,media,search,shortcut,div,html,list,menu,uploads,login,mailform,templavoila_pi1,dlstats_pi1,1,go_stopcslide_pi1';

	/**
	 * customized ignore
	 */
	var $ignoreListCustomized = 'go_lll_piLabel';

	/**
	 * Generate a list of CTypes that are allowed for Translation
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @author	Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-26
	 *
	 * @param	array	$data the data array as reference
	 * @return	Array	diff between ALL CTypes and ignoreList.
	 */
	function getCTypesArray() {
		$ignoreArray = explode(',', $this->ignoreListStandard);
		$ignoreArray = array_merge($ignoreArray, explode(',', $this->ignoreListCustomized));

		$cTypesArray = array_keys($GLOBALS['TCA']['tt_content']['types']);
		$cTypesArray = array_diff($cTypesArray, $ignoreArray);

		return array_merge($cTypesArray);
	}

	/**
	 * Format the list of CTypes that are allowed for Translation
	 * NEEDED BY TCA.
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	array	$data the data array as reference
	 * @return	void
	 */
	function getCTypeItems(&$data){
		$cTypesArray = $this->getCTypesArray();
		foreach ($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] as $cTypeConfig) {
			$cTypeConfigItems[$cTypeConfig[1]] = $cTypeConfig;
		}

		foreach(($cTypesArray) as $cType) {
			$label = $GLOBALS['LANG']->sL($cTypeConfigItems[$cType][0]);
			$data['items'][] = array( $label, $cType);
		}
	}

	/**
	 * This function formats the title for the label
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @author	Marius Stübs <marius@gosign.de>
	 *
	 * @date: 2012-02-17
	 *
	 * @param	array	$params array with table, row and title
	 * @return	void
	 */
	function getLabelTitle(&$params, &$null){
		$label = '';

		$seperator = ': ';
		$valueWraps = array( '<span class="tx-golll-value" style="display: block; width: 500px; height: 14px; overflow: hidden;">', '</span>');
		$labelWraps = array( '<span class="tx-golll-label" style="float: right;">(', ')</span>');
		$allWraps = array( '<span class="tx-golll-all" style="display:block;">', '</span>');

		$label .= $valueWraps[0] . $params['row']['tx_golll_value'] . $valueWraps[1];
		$label .= $labelWraps[0] . $params['row']['tx_golll_langlabel'] . $seperator . $params['row']['tx_golll_label'] . $labelWraps[1];

		$params['title'] = $label;
	}


	/**
	 * This function feeds a hook of templaVoila >= 1.6.0
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @author	Marius Stübs <marius@gosign.de>
	 *
	 * @desc Rewrite the "new element wizard Item List"
	 * @date 2012-01-26
	 *
	 * @return	void	In fact we return $wizardItems by reference.
	 */
	function manipulateWizardItems(&$wizardItems, &$self) {
			// check if we are on a translation page. Page id is "$self->id".
			// if not: return.
		if( $self->id != $this->getTranslationPageUID() ) {
			return;
		}

		$cTypesArray = $this->getCTypesArray();
		$myCType = 'go_lll_piLabel';

		/* Traverse through all wizard items
		 * and remove those not in $cTypesArray.
		 *
		 */
		$removeArray = array();

		foreach ($wizardItems as $key => &$wItem) {
				// Check, if the wizard item CType is allowed to get a translation element
			if (in_array($wItem['tt_content_defValues']['CType'], $cTypesArray) && $this->hasLocallang($wItem['tt_content_defValues']['CType'])) {
				$wItem['title'] = 'Bezeichner-Element für ' . $wItem['title'];
				$wItem['description'] = '';
				$wItem['tt_content_defValues']['tx_golll_ctype'] = $wItem['tt_content_defValues']['CType'];
				$wItem['tt_content_defValues']['CType'] = $myCType;
				$wItem['params'] = '&defVals[tt_content][CType]='.$myCType;
				$wItem['params'] .= '&defVals[tt_content][header]='.urlencode($this->computeHeaderValue($wItem['title']));
				$wItem['params'] .='&defVals[tt_content][tx_golll_ctype]='.$wItem['tt_content_defValues']['tx_golll_ctype'];
			} elseif ($key === 'common') {
					//if this element is the head -> change the header
				$wizardItems['common']['header'] = 'Bezeichner-Elemente';
			} else {
					// If the wizard item CType is not good, remove it afterwards.
				$removeArray[] = $key;
			}
		}
			// Remove unliked wizard items.
		foreach ($removeArray as $key) {
			unset($wizardItems[$key]);
		}

		// @reminder No return is required, because in fact we return $wizardItems by reference.
	}

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
	function handleIncomingCommands_preProcess($command, $redirectLocation, $self) {
		if ($command == 'createNewRecord') {
			$defVals = t3lib_div::_GP('defVals');
			$newRow = is_array ($defVals['tt_content']) ? $defVals['tt_content'] : array();
			if ($newRow['CType'] == 'go_lll_piLabel' && (!empty($newRow))) {
					// now we know what CType we must handle
				$translatedCType = $newRow['tx_golll_ctype'];

				$cUID = $this->checkIfContentElementAlreadyExists($translatedCType);
				if (!$cUID) {
					$this->writeLabelsToDatabase($translatedCType);
				} else {
					$commandParameters = t3lib_div::_GP($command);
					$apiObj = t3lib_div::makeInstance('tx_templavoila_module1');
					$redirectLocation = $GLOBALS['BACK_PATH'].'alt_doc.php?edit[tt_content]['.$cUID.']=edit&returnUrl='.rawurlencode(t3lib_extMgm::extRelPath('templavoila').'mod1/index.php?'.$apiObj->link_getParameters());
					return TRUE;;
				}
			}
		}
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
		$count = preg_match_all('/label\s+index="(.+)"/', $contents, $markerArray);
		$matches = array_unique($markerArray[1]);

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
		if ($table === 'tt_content') {
			if ($row['CType'] === 'go_lll_piLabel') {
				$translatedCType = $row['tx_golll_ctype'];
					// how many Labels are missing? Write and count!
				$count = $this->writeLabelsToDatabase($translatedCType);
					// check if there are existing labels directing to the wrong record
				$count2 = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('COUNT(uid) as count', 'tx_golll_translation', 'tx_golll_ctype = "' . $translatedCType . '" AND parentElement != ' . $row['uid']);
				if ($count2['count'] > 0) {
						//there are existing labels directing to the wrong record? Count them and correct them.
					$count += $count2['count'];
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_golll_translation', 'tx_golll_ctype = "' . $row['tx_golll_ctype'] . '"',  array( 'parentElement' => $row['uid']));
				}


				if ($count > 0 and !$GLOBALS['tx_golll_redirectcount']) {
					//$apiObj = t3lib_div::makeInstance('tx_templavoila_module1');
					$redirectLocation = $GLOBALS['BACK_PATH'].'alt_doc.php?edit[tt_content]['.$row['uid'].']=edit&returnUrl='.rawurlencode(t3lib_extMgm::extRelPath('templavoila').'mod1/index.php?'); //.$apiObj->link_getParameters());
					$GLOBALS['tx_golll_redirectcount'] = 1;
					t3lib_utility_http::redirect($redirectLocation);
				}
				return TRUE;;

			}
		}
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
		$lastUnderscore = strrpos($cType, '_');
		$piName = substr($cType, $lastUnderscore + 1);
		$extName = substr($cType, 0, $lastUnderscore);

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



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_farbcodes/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_farbcodes/mod1/index.php']);
}


?>