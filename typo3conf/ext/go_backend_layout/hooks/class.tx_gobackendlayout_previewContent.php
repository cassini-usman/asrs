<?php
/***************************************************************
*  Copyright notice
*
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
 * New content elements wizard hook for templavoila
 *
 * @author Daniel Agro <agro@gosign.de>
 */
/**
 * The functions in this class were previously written by Mansoor
 * and had XCLASSed templaviola/mod1/index.php
 *
 * It tells the backend how to render the CEs.
 *
 * Templavoila >= 1.6.0 has hooks for that. Therefore
 * the functionallity comes from the past, but the
 * way/method comes from the present.
 *
 * @date 2012-07-06
 * @author Daniel Agro <agro@gosign.de>
 */
class tx_gobackendlayout_previewContent {
	/**
	 * Instance of class t3lib_TCEforms
	 *
	 * @var	t3lib_TCEforms
	 */
	protected $t3lib_TCEforms;

	/**
	 * list with fieldnames which have to be ignored
	 *
	 * @var	String
	 */
	protected $fieldIgnoreString = 'tx_goimageeditbe_croped_image';

	/**
	 * class constuctor
	 */
	public function __construct() {
		$this->t3lib_TCEforms = t3lib_div::makeInstance('t3lib_TCEforms');
	}

	/**
	 * This function renders all default content for go_backendlayout.
	 *
	 * @date 2012-07-06
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	array		$row: The row of tt_content containing the content element record.
	 * @param	String		$table	Default is 'tt_content'.
	 * @param	String		$alreadyRendered
	 * @param	Object		$self is $this for class tx_templavoila_module1 (extends t3lib_SCbase)
	 *
	 * @return	String		HTML preview content
	 */
	function renderPreviewContent_preProcess($row, $table = 'tt_content', &$alreadyRendered, &$self) {
		t3lib_div::loadTCA($table);

		$tvSubElement = $this->renderTemplavoilaSubElements($row, $table, $self);
		$output = $this->renderPluginDefault($row, $table);

		if ($output) {
			$alreadyRendered = TRUE;
		}

		return $tvSubElement . $output;
	}

	/**
	 * render templavoila subelements if it is a localized templavoila element
	 *
	 * @date 2012-07-11
	 * @author Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	array	$row: The row of tt_content containing the content element record.
	 * @param	String	$table: The table of the row
	 * @param	Object	$self: is $this for class tx_templavoila_module1 (extends t3lib_SCbase)
	 *
	 * @return	string	the HTML output, may be empty
	 */
	protected function renderTemplavoilaSubElements($row, $table, &$self) {
		if ($row['CType'] !== 'templavoila_pi1' || !$row['sys_language_uid'] || !$row['l18n_parent']) {
			return '';
		}

		$output = '';
		$contentTreeData = $self->apiObj->getContentTree($table, $row);
		if (!is_array($contentTreeData['tree']['sub']) || empty($contentTreeData['tree']['sub'])) {
			return '';
		}

		$pid = $contentTreeData['tree']['el']['pid'];
		if ($contentTreeData['tree']['el']['pid'] != $self->rootElementUid_pidForContent && $contentTreeData['tree']['el']['_ORIG_uid']) {
			$record = t3lib_BEfunc::getMovePlaceholder('tt_content', $contentTreeData['tree']['el']['uid']);
			if (is_array($record) && $record['t3ver_move_id'] == $contentTreeData['tree']['el']['uid']) {
				$pid = $record['pid'];
			}
		}
		$row = t3lib_BEfunc::getRecordWSOL('pages', $pid);
		$calcPerms = $GLOBALS['BE_USER']->calcPerms($row);

		foreach (array_keys($contentTreeData['tree']['sub']) as $sheetKey) {
			$output .= $self->render_framework_subElements($contentTreeData['tree'], $self->currentLanguageKey, $sheetKey, $calcPerms);
		}

		return $output;
	}

	/**
	 * render Plugin in the Backend for pageview
	 *
	 * @date 2012-07-09
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	array	$row: the row of the current Element
	 * @param	String	$table: The table of the row
	 *
	 * @return string the HTML output
	 */
	function renderPluginDefault($row, $table) {
		$output = '';

			// make instance of mustache
		$mustache = tx_golibmustache::create();
			// set the tempalte
		$mustache->setTemplatePath($this->getTemplate($row['CType']));

			// get the parsed show item array
		$parsedShowItemArray = $this->getParsedShowItem($row, $table);

		$mustache->defaultFields = array();
		$mustache->typeValue = $row[$GLOBALS['TCA'][$table]['ctrl']['type']];
		foreach ($parsedShowItemArray as $fieldInfo) {
			if (empty($fieldInfo['field'])) {
				continue;
			}
				// save the singleFieldContent in a mustache class field for plugin specific be_templates
			$mustache->$fieldInfo['field'] = $this->getSingleFieldForContent($fieldInfo, $row, $table);
				// save the singleFieldContent in a mustache class field for default be_templates
			$mustache->defaultFields[] = $mustache->$fieldInfo['field'];
		}

		$output = $mustache->renderTemplate('template_be');

		return $output;
	}


	/**
	 * This function fetches the template file which has to be used for rendering
	 *
	 * @date 2012-07-09
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	array	$cType: the row of the current Element
	 *
	 * @return string the HTML output
	 */
	function getTemplate($cType) {
		$extTemplatePath = '';
		$extensionNameParts = explode('_', $cType);
		$pluginName = array_pop($extensionNameParts);
		$extensionName = implode('_', $extensionNameParts);

			//check if extension is loaded and set the path to the template folder
		if (t3lib_extMgm::isLoaded($extensionName)) {
			$extTemplatePath = t3lib_extMgm::extPath($extensionName) . $pluginName;
		}

			// check if plugin be_template exist. if not -> use go_backend_layout template folder
		if (!file_exists($extTemplatePath . '/template_be.mustache.html')) {
			$extTemplatePath = t3lib_extMgm::extPath('go_backend_layout') . 'res/templates';
		}

		return $extTemplatePath;
	}


	/**
	 * This function parses the showitem string for the given plugin
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 * @author Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	array	$row: content row
	 * @param	String	$table: The table of the row	Default is 'tt_content'
	 *
	 * @return	array	the parsed showitem string for the given plugin
	 */
	function getParsedShowItem($row, $table = 'tt_content') {
		$typeValue = t3lib_BEfunc::getTCAtypeValue($table, $row);
		$showItemTempArray = t3lib_BEfunc::getTCAtypes($table, $row, 0);
		$excludeFields = $this->t3lib_TCEforms->getExcludeElements($table, $row, $typeValue);
		$excludeFields[] = '--div--';
		$excludeFields[] = '--linebreak--';
		$addFields = $this->t3lib_TCEforms->getFieldsToAdd($table, $row, $typeValue);
		$showFields = $this->getShowFieldsByType($typeValue, $table);

		$showItemArray = array();
		foreach ($showItemTempArray as &$showItem) {
			if (!$this->checkShowField($table, $showItem['field'], $excludeFields, $showFields)) {
				continue;
			}
			$showItemArray[] = $showItem;
			if (count($addFields[0]) && $showItem['field'] == $addFields[1]) {
				$addFieldsArray = array();
				foreach ($addFields[0] as $single) {
					$singleInfo = t3lib_div::trimExplode(';', $single, FALSE);
					if (!$this->checkShowField($table, $singleInfo[0], $excludeFields, $showFields)) {
						continue;
					}
					$addFieldsArray[] = $this->getFormatedFieldArray($table, $singleInfo);
				}
				$showItemArray = array_merge($showItemArray, $addFieldsArray);
			}
		}

		$showItemTempArray = $showItemArray;
		$showItemArray = array();
		foreach ($showItemTempArray as &$showItem) {
			if (!$showItem['title']) {
				$showItem['title'] = $GLOBALS['TCA'][$table]['columns'][$showItem['field']]['label'];
			}
			$showItem['type'] = $GLOBALS['TCA'][$table]['columns'][$showItem['field']]['config']['type'];
			$showItemArray[] = $showItem;
			if ($showItem['palette']) {
				$palette = $this->getPaletteElements($table, $row, $showItem['palette'], $excludeFields, $showFields);
				if (!empty($palette)) {
					$showItemArray = array_merge($showItemArray, $palette);
				}
			}
		}

		return $showItemArray;
	}

	/**
	 * This function parses the showitem string for the given plugin
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	String	$table: The table of the row
	 * @param	array	$row: content row
	 * @param	String	$paletteName: the palette name
	 * @param	array	$excludeFields: list with field names which has to be excluded
	 * @param	array	$showFields: list with field names which are allowed to be shown
	 *
	 * @return	array	the parsed showitem string for the given plugin
	 */
	function getPaletteElements($table, $row, $paletteName, $excludeFields, $showFields) {
		$paletteItemsArray = array();
			// get the palette string from TCA
		$paletteString = $GLOBALS['TCA'][$table]['palettes'][$paletteName]['showitem'];
		$paletteArray = t3lib_div::trimExplode(',', $paletteString, FALSE);
			// traverse the palette
		foreach ($paletteArray as $single) {
				// safe the palette field in the return array
			$singleInfo = t3lib_div::trimExplode(';', $single, FALSE);
			if (!$this->checkShowField($table, $singleInfo[0], $excludeFields, $showFields)) {
				continue;
			}
			$paletteItemsArray[] = $this->getFormatedFieldArray($table, $singleInfo);

			if (!empty($singleInfo[2])) {
				$palette = $this->getPaletteElements($table, $row, $singleInfo[2], $excludeFields, $showFields);
				if (!empty($palette)) {
					$paletteItemsArray = array_merge($paletteItemsArray, $palette);
				}
			}
		}

		return $paletteItemsArray;
	}


	/**
	 * This function fetches show field array
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	array	$typeValue: the type value
	 * @param	String	$table: The table of the row	Default is 'tt_content'
	 *
	 * @return	array	array with fieldnames which are allowed to be shown
	 */
	function getShowFieldsByType($typeValue, $table = 'tt_content') {
		$showFieldsArray = t3lib_div::trimExplode(',', $this->getShowItem($typeValue, $table), TRUE);
		return $showFieldsArray ? $showFieldsArray : array();
	}


	/**
	 * This function fetches the showitem String for the given plugin
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	array	$typeValue: the type value
	 * @param	String	$table: The table of the row	Default is 'tt_content'
	 *
	 * @return	String	the showitem string for the given plugin
	 */
	function getShowItem($typeValue, $table = 'tt_content') {
		switch ($typeValue) {
			case 'header':
				$showItem = 'CType, header, header_link, subheader, starttime, endtime';
				break;
			case 'textpic':
				$showItem = 'CType, header, header_link, bodytext, tx_damttcontent_files, imagecaption, imageorient, starttime, endtime';
				break;
			case 'image':
				$showItem = 'CType, header, header_link, tx_damttcontent_files, imagecaption, imageorient, starttime, endtime';
				break;
			case 'text':
			case 'bullets':
			case 'table':
				$showItem = 'CType, header, header_link, bodytext, starttime, endtime';
				break;
			case 'templavoila_pi1':
				$showItem = 'CType';
				break;
			default:
				$showItem = '';
				break;
		}

		return $showItem;
	}


	/**
	 * This function returns the label and the value to store.
	 * in the mustache object.
	 * The different fieldTypes are handled here.
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	array	$singleField: the field information
	 * @param	string	$rowValue: the value for this field fetched from the content row
	 * @param	String	$table: The table of the row
	 *
	 * @return	array	label and value for this field
	 */
	function getSingleFieldForContent($singleField, $row, $table) {
		$rowValue = $row[$singleField['field']];

			// store the locallang path for go_backend_layout
		$localLangPath = 'LLL:EXT:go_backend_layout/locallang.xml:';
		$singleFieldcontent = array();

			// check given and use go_backend_layout as fallback
		$labelPath = $singleField['title'] ? $singleField['title'] : $localLangPath . $singleField['type'];
		$singleFieldcontent['label'] = $GLOBALS['LANG']->sL($labelPath);
		$singleFieldcontent['fieldName'] = $singleField['field'];
		$singleFieldcontent['isType'] = '';

			// first: get the value from t3lib_BEfunc
		$singleFieldcontent['value'] = t3lib_BEfunc::getProcessedValue($table, $singleField['field'], $rowValue);

			// second: switch the type and override value from t3lib_BEfunc, if necessary
		switch($singleField['type']) {
			case 'text':
					// strip the tags (for case rte) and set breaks to the text
					// notice: field values won't get escaped in the template
				$singleFieldcontent['value'] = nl2br(strip_tags($rowValue, '<p><br />'));
				break;
			case 'input':
					// if it is a link field
				if ($this->isLinkField($singleField['field'], $table)) {
						// fetch the link conf
					$linkConfArray = t3lib_div::trimExplode(' ', $rowValue);
						// check if internal link
					if (is_numeric($linkConfArray[0])) {
							// get the content string which gets build like this: page_title (ID: page_id)
						$singleFieldcontent['value'] = $this->getLinkFieldContent($linkConfArray[0]);
					} else {
							// if no internal link, just show the url
						$singleFieldcontent['value'] = $rowValue;
					}
				}
				break;
			case 'group':
					// if it is a dam field
				if ($this->isDamField($singleField['field'], $row, $table)) {
						// get the dam field and put file link into the row, so that thumbCode can handle it
					$damFiles = tx_dam_db::getReferencedFiles($table, $row['uid'], $singleField['field'], 'tx_dam_mm_ref', '', array(), '', 'sorting_foreign');
					if (is_array($damFiles) && count($damFiles) > 0) {
						$row[$singleField['field']] = implode(',', $damFiles['files']);
					}
					$singleFieldcontent['value'] = t3lib_BEfunc::thumbCode($row, $table, $singleField['field'], $GLOBALS['BACK_PATH'], '', '');
				} elseif ($this->isPagesField($singleField['field'], $row, $table)) {
						// just get the the link content for all given pages
					$pagesArray = t3lib_div::trimExplode(',', $rowValue);
					foreach ($pagesArray as $single) {
						if ($single) {
							$singleFieldcontent['value'] .= $this->getLinkFieldContent($single) . '<br />';
						}
					}
				}
				break;
			case 'select':
				if ($singleField['field'] === $GLOBALS['TCA'][$table]['ctrl']['type']) {
					if ($rowValue === 'templavoila_pi1') {
						$newValue = $this->getTemplateObjectTitle($row['tx_templavoila_to']);
						if ($newValue) {
							$singleFieldcontent['value'] = $newValue;
						}
						if ($GLOBALS['BE_USER']->isAdmin()) {
							$singleFieldcontent['value'] .= ' <em>(ID: ' . $row['uid'] . ')</em>';
						}
					} elseif ($GLOBALS['BE_USER']->isAdmin()) {
						$singleFieldcontent['value'] .= ' <em>(' . $rowValue . ' - ID: ' . $row['uid'] . ')</em>';
					}
					$singleFieldcontent['label'] = '';
					$singleFieldcontent['isType'] = 'typeRow';
				}
				break;
			case 'inline':
			default:
				break;
		}

		return $singleFieldcontent;
	}

	/**
	 * Gets the title of the given template object id
	 *
	 * @date 2012-07-17
	 * @author Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	integer	$templateObjectId: the template object id
	 *
	 * @return	string	The template object title
	 */
	function getTemplateObjectTitle($templateObjectId) {
		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('title', 'tx_templavoila_tmplobj', 'uid = ' . (int) $templateObjectId, '', '', '');
		return $row['title'] ? $row['title'] : '';
	}

	/**
	 * Checks if the given field is a link field
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	String	$fieldName: field name to check
	 * @param	String	$table: The table of the row	Default is 'tt_content'
	 *
	 * @return	Boolean	TRUE if is link field
	 */
	function isLinkField($fieldName, $table = 'tt_content') {
		return is_array($GLOBALS['TCA'][$table]['columns'][$fieldName]['config']['wizards']['link']) ? TRUE : FALSE;
	}


	/**
	 * Checks if the given field is a dam field
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	String	$fieldName: field name to check
	 * @param	String	$table: The table of the row	Default is 'tt_content'
	 *
	 * @return	Boolean	TRUE if is dam field
	 */
	function isDamField($fieldName, $row, $table = 'tt_content') {
		return $GLOBALS['TCA'][$table]['columns'][$fieldName]['config']['MM'] == 'tx_dam_mm_ref' && is_numeric($row[$fieldName]) ? TRUE : FALSE;
	}

	/**
	 * Checks if the given field is a pages field
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	String	$fieldName: field name to check
	 * @param	String	$table: The table of the row	Default is 'tt_content'
	 *
	 * @return	Boolean	TRUE if is pages field
	 */
	function isPagesField($fieldName, $row, $table = 'tt_content') {
		return ($GLOBALS['TCA'][$table]['columns'][$fieldName]['config']['internal_type'] == 'db' && $GLOBALS['TCA'][$table]['columns'][$fieldName]['config']['allowed'] == 'pages') ? TRUE : FALSE;
	}

	/**
	 * This function builds the content string for the preview content
	 *
	 * @date 2012-07-10
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @param	String	$pageID: uid to find the page title for
	 *
	 * @return	String	content string for the preview for link fields
	 */
	function getLinkFieldContent($pageID) {
		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('title', 'pages', 'uid = ' . $pageID, '', '', '');
		return $row['title'] . ' (ID: ' . $pageID . ')';
	}

	/**
	 * This function checks if the given field should be shown
	 *
	 * @date 2012-07-20
	 * @author Daniel Agro <agro@gosign.de>
	 * @author Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	String	$table: The table of the row
	 * @param	String	$fieldName: field to check
	 * @param	array	$excludeFields: list with field names which has to be excluded
	 * @param	array	$showFields: list with field names which are allowed to be shown
	 *
	 * @return	boolean	TRUE, if field should be shown
	 */
	protected function checkShowField($table, $fieldName, $excludeFields, $showFields) {
		$fieldIgnoreArray = t3lib_div::trimExplode(',', $this->fieldIgnoreString);

		$show = TRUE;
		$show &= !in_array($fieldName, $excludeFields);
		$show &= (empty($showFields) || in_array($fieldName, $showFields) || $fieldName === '--palette--');
		$show &= ($fieldName != $GLOBALS['TCA'][$table]['ctrl']['languageField']);
		$show &= (!in_array($fieldName, $fieldIgnoreArray));

		return $show;
	}


	/**
	 * This function checks if the given field should be shown
	 *
	 * @date 2012-07-20
	 * @author Daniel Agro <agro@gosign.de>
	 * @author Caspar Stuebs <caspar@gosign.de>
	 *
	 * @param	String	$table: The table of the row
	 * @param	array	$fieldArray: array which has to be formated
	 *
	 * @return	array	The formatted field array
	 */
	protected function getFormatedFieldArray($table, $fieldArray) {
		$defaultExtras = is_array($GLOBALS['TCA'][$table]['columns'][$fieldArray[0]]) ? $GLOBALS['TCA'][$table]['columns'][$fieldArray[0]]['defaultExtras'] : '';
		$specConfParts = t3lib_BEfunc::getSpecConfParts($fieldArray[3], $defaultExtras);

		$formatedFieldArray = array(
			'field' => $fieldArray[0],
			'title' => $fieldArray[1] ? $fieldArray[1] : $GLOBALS['TCA'][$table]['columns'][$fieldArray[0]]['label'],
			'palette' => $fieldArray[2],
			'spec' => $specConfParts,
			'origString' => implode(';', $fieldArray),
			'type' => $GLOBALS['TCA'][$table]['columns'][$fieldArray[0]]['config']['type']
		);

		return $formatedFieldArray;
	}
}
?>