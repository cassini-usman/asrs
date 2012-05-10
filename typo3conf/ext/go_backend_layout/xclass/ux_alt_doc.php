<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Marius Stuebs <marius@gosign.de>
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
 * Add the languageSwitch to pages and pages_language_overlay.
 * Until now, only normal content elements had it.
 *
 * Does not implement the "[NEW] English" functionallity: Shows
 * only existing pages_language_overlay instances.
 *
 * @author Marius Stuebs <marius@gosign.de>
 * @date 2012-02-13
 */
class ux_SC_alt_doc extends SC_alt_doc {
	/***************************
	 *
	 * Localization stuff
	 *
	 ***************************/

	/**
	 * Make selector box for creating new translation for a record or switching to edit the record in an existing language.
	 * Displays only languages which are available for the current page.
	 *
	 * @param	string		Table name
	 * @param	integer		uid for which to create a new language
	 * @param	integer		pid of the record
	 * @return	string		<select> HTML element (if there were items for the box anyways...)
	 */
	function languageSwitch($table, $uid, $pid=NULL) {
		$content = parent::languageSwitch($table, $uid, $pid);

		if (empty($content)) {
			$tableIsLocalizable = FALSE;

			if ($GLOBALS['TCA'][$table]['ctrl']['transForeignTable']) {
				$tableIsLocalizable = TRUE;
				$tableOrig = $table;
				$tableTranslation = $GLOBALS['TCA'][$table]['ctrl']['transForeignTable'];
			} elseif ($GLOBALS['TCA'][$table]['ctrl']['transOrigPointerTable']) {
				$tableIsLocalizable = TRUE;
				$tableOrig = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerTable'];
				$tableTranslation = $table;
			}

			if ($tableIsLocalizable) {
				$languageField = $GLOBALS['TCA'][$tableTranslation]['ctrl']['languageField'];
				$transOrigPointerField = $GLOBALS['TCA'][$tableTranslation]['ctrl']['transOrigPointerField'];
			}
		}

			// table editable and activated for languages?
		if ($languageField && $transOrigPointerField && $GLOBALS['BE_USER']->check('tables_modify', $tableOrig) && $GLOBALS['BE_USER']->check('tables_modify', $tableTranslation)) {

				// if $table is pages (and only! in that case), we need to set the pid to uid
			if ($table == 'pages') {
				$pid = $uid;
			}

				// get all available languages for the page
			$langRows = $this->getLanguages($pid);

				// page available in other languages than default language?
			if (is_array($langRows) && count($langRows)>1) {

				$rowsByLang = array();
					// default language
				$currentLanguage = 0;
				$rowsByLang[0] = array('uid' => $pid);
				$fetchFields = 'uid,'.$languageField.','.$transOrigPointerField;

					// get record in other languages to see what's already available
				$translations = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					$fetchFields,
					$tableTranslation,
					'pid='.intval($pid).
						' AND '.$languageField.'>0'.
						t3lib_BEfunc::deleteClause($tableTranslation).
						t3lib_BEfunc::versioningPlaceholderClause($tableTranslation)
				);
				foreach ($translations as $row) {
					$rowsByLang[$row[$languageField]] = $row;
					if ($uid == $row['uid']) {
						$currentLanguage = $row[$languageField];
					}
				}

				$langSelItems=array();
				foreach ($langRows as $lang) {
					if ($GLOBALS['BE_USER']->checkLanguageAccess($lang['uid'])) {

						$newTranslation = isset($rowsByLang[$lang['uid']]) ? '' : ' ['.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.new',1).']';

							// create url for creating a localized record
						if($newTranslation) {
							// $href = $this->doc->issueCommand(
								// '&cmd['.$table.']['.$rowsByLang[0]['uid'].'][localize]='.$lang['uid'],
								// $this->backPath.'alt_doc.php?justLocalized='.rawurlencode($table.':'.$rowsByLang[0]['uid'].':'.$lang['uid']).'&returnUrl='.rawurlencode($this->retUrl) . t3lib_BEfunc::getUrlToken('editRecord')
							// );

							// create edit url
						} else {
							if ($lang['uid'] == 0) {
								$tableNow = $tableOrig;
							} else {
								$tableNow = $tableTranslation;
							}
							$href = $this->backPath.'alt_doc.php?';
							$href .= '&edit['.$tableNow.']['.$rowsByLang[$lang['uid']]['uid'].']=edit';
							$href .= '&returnUrl='.rawurlencode($this->retUrl) . t3lib_BEfunc::getUrlToken('editRecord');
						}

						$langSelItems[$lang['uid']]='
								<option value="'.htmlspecialchars($href).'"'.($currentLanguage==$lang['uid']?' selected="selected"':'').'>'.htmlspecialchars($lang['title'].$newTranslation).'</option>';
					}
				}

					// If any languages are left, make selector:
				if (count($langSelItems) > 1) {
					$onChange = 'if(this.options[this.selectedIndex].value){window.location.href=(this.options[this.selectedIndex].value);}';
					$content = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.xml:LGL.language',1).' <select name="_langSelector" onchange="'.htmlspecialchars($onChange).'">
							'.implode('',$langSelItems).'
						</select>';
				}
			}
		}
		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/ux_alt_doc.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/xclass/ux_alt_doc.php']);
}

?>