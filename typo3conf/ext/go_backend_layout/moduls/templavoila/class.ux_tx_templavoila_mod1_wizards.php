<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2006  Robert Lemke (robert@typo3.org)
*  All rights reserved
*
*  script is part of the TYPO3 project. The TYPO3 project is
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
 * Submodule 'wizards' for the templavoila page module
 *
 * $Id: class.tx_templavoila_mod1_wizards.php 4964 2007-02-18 17:28:19Z liels_bugs $
 *
 * @author     Robert Lemke <robert@typo3.org>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   62: class tx_templavoila_mod1_wizards
 *   78:     function init(&$pObj)
 *
 *              SECTION: Wizards render functions
 *  103:     function renderWizard_createNewPage ($positionPid)
 *
 *              SECTION: Wizard related helper functions
 *  241:     function renderTemplateSelector ($positionPid, $templateType='tmplobj')
 *  355:     function createPage($pageArray,$positionPid)
 *  389:     function getImportObject()
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Submodule 'Wizards' for the templavoila page module
 *
 * Note: This class is closely bound to the page module class and uses many variables and functions directly. After major modifications of
 *       the page module all functions of this wizard class should be checked to make sure that they still work.
 *
 * @author		Robert Lemke <robert@typo3.org>
 * @package		TYPO3
 * @subpackage	tx_templavoila
 */


class ux_tx_templavoila_mod1_wizards extends tx_templavoila_mod1_wizards {

	/********************************************
	 *
	 * Wizard related helper functions
	 *
	 ********************************************/

	/**
	 * Renders the template selector.
	 *
	 * @param	integer		Position id. Can be positive and negative depending of where the new page is going: Negative always points to a position AFTER the page having the abs. value of the positionId. Positive numbers means to create as the first subpage to another page.
	 * @param	string		$templateType: The template type, 'tmplobj' or 't3d'
	 * @return	string		HTML output containing a table with the template selector
	 */
	function renderTemplateSelector ($positionPid, $templateType='tmplobj') {
		global $LANG, $TYPO3_DB;

		$storageFolderPID = $this->apiObj->getStorageFolderPid($positionPid);
		$tmplHTML = array();

		switch ($templateType) {
			case 'tmplobj':
						// Create the "Default template" entry
				$previewIconFilename = $GLOBALS['BACK_PATH'].'../'.t3lib_extMgm::siteRelPath($this->extKey).'res1/default_previewicon.gif';
				$previewIcon = '<input type="image" class="c-inputButton" name="i0" value="0" src="'.$previewIconFilename.'" title="" />';
				$description = htmlspecialchars($LANG->getLL ('template_descriptiondefault'));
				/*
				$tmplHTML [] = '<table style="float:left; width: 100%;" valign="top"><tr><td colspan="2" nowrap="nowrap">
					<h3 class="bgColor3-20">'.htmlspecialchars($LANG->getLL ('template_titledefault')).'</h3></td></tr>
					<tr><td valign="top">'.$previewIcon.'</td><td width="120" valign="top"><p>'.$description.'</p></td></tr></table>';
				*/

				$tTO = 'tx_templavoila_tmplobj';
				$tDS = 'tx_templavoila_datastructure';
				$where = $tTO . '.parent=0 AND ' . $tTO . '.pid=' .
						intval($storageFolderPID).' AND ' . $tDS . '.scope=1' .
						$this->buildRecordWhere($tTO) . $this->buildRecordWhere($tDS) .
						t3lib_befunc::deleteClause ($tTO).t3lib_befunc::deleteClause ($tDS).
						t3lib_BEfunc::versioningPlaceholderClause($tTO).t3lib_BEfunc::versioningPlaceholderClause($tDS);

				$res = $TYPO3_DB->exec_SELECTquery (
					$tTO . '.*',
					$tTO . ' LEFT JOIN ' . $tDS . ' ON ' . $tTO . '.datastructure = ' . $tDS . '.uid',
					$where
				);

				// start elio@gosign 06-06-2011: Option to select no template
				$rows = array();
				while (false !== ($row = $TYPO3_DB->sql_fetch_assoc($res)))	{
					$rows[] = $row;
				}
				$rows[] = array(
					'uid' => '0',
					'title' => $LANG->getLL('wizard.page.new.notemplate'),
					'description' => $LANG->getLL('wizard.page.new.notemplate.description'),
				);
				// end elio@gosign

				foreach ( $rows as $row ) {


					// #
					// ### Mansoor Ahmad - I deselected here some TV Pagetemplates
					// #

					//echo $row['uid'] . '<br />';
					//$ignoreList	=	'6,9,11';
					//if(!in_array($row['uid'],explode(',',$ignoreList)))
					//{
						// Check if preview icon exists, otherwise use default icon:
						$tmpFilename = 'uploads/tx_templavoila/'.$row['previewicon'];
						$previewIconFilename = (@is_file(PATH_site.$tmpFilename)) ? ($GLOBALS['BACK_PATH'].'../'.$tmpFilename) : ($GLOBALS['BACK_PATH'].'../'.t3lib_extMgm::siteRelPath($this->extKey).'res1/default_previewicon.gif');

						// Note: we cannot use value of image input element because MSIE replaces this value with mouse coordinates! Thus on click we set value to a hidden field. See http://bugs.typo3.org/view.php?id=3376
						$previewIcon = '<input type="image" class="c-inputButton" name="i' .$row['uid'] . '" onclick="document.getElementById(\'data_tx_templavoila_to\').value='.$row['uid'].'" src="'.$previewIconFilename.'" title="" />';
						$description = $row['description'] ? htmlspecialchars($row['description']) : $LANG->getLL ('template_nodescriptionavailable');
						$tmplHTML [] = '<table style="width: 100%;" valign="top"><tr><td colspan="2" nowrap="nowrap"><h3 class="bgColor3-20">'.htmlspecialchars($row['title']).'</h3></td></tr>'.
						'<tr><td valign="top">'.$previewIcon.'</td><td width="120" valign="top"><p>'.$description.'</p></td></tr></table>';
					//}

				}


				$tmplHTML[] = '<input type="hidden" id="data_tx_templavoila_to" name="data[tx_templavoila_to]" value="0" />';
				break;

			case 't3d':
				if (t3lib_extMgm::isLoaded('impexp'))	{

						// Read template files from a certain folder. I suggest this is configurable in some way. But here it is hardcoded for initial tests.
					$templateFolder = PATH_site.$GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'].'/export/templates/';
					$files = t3lib_div::getFilesInDir($templateFolder,'t3d,xml',1,1);

						// Traverse the files found:
					foreach($files as $absPath)	{
							// Initialize the import object:
						$import = $this->getImportObject();
						if ($import->loadFile($absPath))	{
							if (is_array($import->dat['header']['pagetree']))	{	// This means there are pages in the file, we like that...:

									// Page tree:
								reset($import->dat['header']['pagetree']);
								$pageTree = current($import->dat['header']['pagetree']);


									// Thumbnail icon:
								if (is_array($import->dat['header']['thumbnail']))	{
									$pI = pathinfo($import->dat['header']['thumbnail']['filename']);
									if (t3lib_div::inList('gif,jpg,png,jpeg',strtolower($pI['extension'])))	{

											// Construct filename and write it:
										$fileName = PATH_site.
													'typo3temp/importthumb_'.t3lib_div::shortMD5($absPath).'.'.$pI['extension'];
										t3lib_div::writeFile($fileName, $import->dat['header']['thumbnail']['content']);

											// Check that the image really is an image and not a malicious PHP script...
										if (getimagesize($fileName))	{
												// Create icon tag:
											$iconTag = '<img src="'.$this->doc->backPath.'../'.substr($fileName,strlen(PATH_site)).'" '.$import->dat['header']['thumbnail']['imgInfo'][3].' vspace="5" style="border: solid black 1px;" alt="" />';
										} else {
											t3lib_div::unlink_tempfile($fileName);
											$iconTag = '';
										}
									}
								}

								$aTagB = '<a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('templateFile' => $absPath))).'">';
								$aTagE = '</a>';
								$tmplHTML [] = '<table style="float:left; width: 100%;" valign="top"><tr><td colspan="2" nowrap="nowrap">
					<h3 class="bgColor3-20">'.$aTagB.htmlspecialchars($import->dat['header']['meta']['title'] ? $import->dat['header']['meta']['title'] : basename($absPath)).$aTagE.'</h3></td></tr>
					<tr><td valign="top">'.$aTagB.$iconTag.$aTagE.'</td><td valign="top"><p>'.htmlspecialchars($import->dat['header']['meta']['description']).'</p>
						<em>Levels: '.(count($pageTree)>1 ? 'Deep structure' : 'Single page').'<br/>
						File: '.basename($absPath).'</em></td></tr></table>';

							}
						}
					}
				}
				break;

		}

		if (is_array($tmplHTML) && count($tmplHTML)) {
			$counter = 0;
			$content .= '<table class="tv_select">';
			foreach ($tmplHTML as $single) {
				$content .= ($counter ? '':'<tr>').'<td valign="top">'.$single.'</td>'.($counter ? '</tr>':'');
				$counter ++;
				if ($counter > 1) { $counter = 0; }
			}
			$content .= '</table>';

			// start ELIO
			// add Non-Templavoila-Options (DOKTYPE) ---  by elio@gosign 31-05-2011
			global $TCA;
			t3lib_div::loadTCA('pages');
			// create a standard TCE-Form-Element
			$this->tceforms = t3lib_div::makeInstance('t3lib_TCEforms');
			$this->tceforms->backPath = $GLOBALS['BACK_PATH'];
			$this->tceforms->initDefaultBEMode();
			$conf = array( // get config from TCA
					'itemFormElName' => 'data[doktype]',
					'fieldConf' => array('config' => $TCA['pages']['columns']['doktype']['config']),
			);
			// create an optional submit Button that shows up when no standard-doktype selected
			$submitButton = '<div class="submitBox" style="display: none; margin: 20px 0 0 0"><input type="submit" value="'.$LANG->getLL('wizard.page.new').'" /></div>';
			// render and wrap the Field
			$doktype = $this->tceforms->getSingleField_typeSelect('pages','doktype',array('doktype'=>1),$conf);
			$doktype = '<label style="font-size:12px;padding:0 5px;">'.$LANG->getLL('wizard.page.doktype').'</label>'.$doktype;
			$doktype = '<div class="doktypeBox" style="padding-bottom:15px;">'.$doktype.'</div>';
			$doktype .= '<script type="text/javascript" src="../../../../fileadmin/templates/javascript/jquery.min.js"></script><script type="text/javascript">
/*<![CDATA[*/ <!--
jQuery(document).ready( function () {
	jQuery(".doktypeBox select").change( function() {
		if( jQuery(".doktypeBox select option:selected").val() == 1 ) { // standard page -> show Templavoila options
			jQuery(".tv_select").show();
			jQuery(".submitBox").hide();
		} else { // other doktype -> no TV
			jQuery(".tv_select").hide();
			jQuery(".submitBox").show();
		}
	});
});
// -->
/*]]>*/</script>';
			$content = $doktype . $content . $submitButton;
			// end ELIO
		}

		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/class.ux_tx_templavoila_mod1_wizards.php'])    {
				include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/class.ux_tx_templavoila_mod1_wizards.php']);
}

?>