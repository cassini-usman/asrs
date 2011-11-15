<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2006 Robert Lemke (robert@typo3.org)
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
 * New content elements wizard for templavoila
 *
 * $Id: db_new_content_el.php 8140 2008-02-04 21:17:33Z dmitry $
 * Originally based on the CE wizard / cms extension by Kasper Skaarhoj <kasper@typo3.com>
 * XHTML compatible.
 *
 * @author		Robert Lemke <robert@typo3.org>
 * @coauthor	Kasper Skaarhoj <kasper@typo3.com>
 */



class ux_tx_templavoila_dbnewcontentel extends tx_templavoila_dbnewcontentel {

	protected $myLanguageFiles = array();
	private $coreCTypeList = 'header,text,textpic,image,table,bullets,html';



	/**
	 * Initialize internal variables.
	 *
	 * @return	void
	 */
	function init()	{
		global $BE_USER,$BACK_PATH,$TBE_MODULES_EXT;

		if(t3lib_div::_GP('type') == 'ajax'){
			echo $this->saveRights();
			die();
		}

			// Setting class files to include:
		if (is_array($TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']))	{
			$this->include_once = array_merge($this->include_once,$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']);
		}

			// Setting internal vars:
		$this->id = intval(t3lib_div::_GP('id'));
		$this->parentRecord = t3lib_div::_GP('parentRecord');
		$this->altRoot = t3lib_div::_GP('altRoot');
		$this->defVals = t3lib_div::_GP('defVals');

			// Starting the document template object:
		$this->doc = t3lib_div::makeInstance('mediumDoc');
		$this->doc->docType= 'xhtml_trans';
		$this->doc->backPath = $BACK_PATH;
		$this->doc->JScode = '
		<script type="text/javascript" src="../../../../typo3conf/ext/go_backend_layout/lib/jQuery.js"></script>
		<script type="text/javascript" src="../../../../typo3conf/ext/go_backend_layout/lib/wizardList.js"></script>';

		#
		### Mansoor Ahmad - Dont know why it used
		#
		//$this->doc->form='<form action="" name="editForm">';

			// Getting the current page and receiving access information (used in main())
		$perms_clause = $BE_USER->getPagePermsClause(1);
		$pageinfo = t3lib_BEfunc::readPageAccess($this->id,$perms_clause);
		$this->access = is_array($pageinfo) ? 1 : 0;


		$this->apiObj = t3lib_div::makeInstance ('tx_templavoila_api');

			// If no parent record was specified, find one:
		if (!$this->parentRecord) {
			$mainContentAreaFieldName = $this->apiObj->ds_getFieldNameByColumnPosition ($this->id, 0);
			if ($mainContentAreaFieldName != FALSE) {
				$this->parentRecord = 'pages:'.$this->id.':sDEF:lDEF:'.$mainContentAreaFieldName.':vDEF:0';
			}
		}
	}



	/**
	 * Creating the module output.
	 *
	 * @return	void
	 * @todo	provide position mapping if no position is given already. Like the columns selector but for our cascading element style ...
	 */
	function main()	{
		global $LANG,$BACK_PATH,$BE_USER;

		if ($this->id && $this->access)	{

			$GOBACKEND = $LANG;
			$GOBACKEND->includeLLFile('EXT:go_backend_layout/moduls/templavoila/locallang.xml');
				// Creating content
			$this->content='';
			$this->content.=$this->doc->startPage($LANG->getLL('newContentElement'));
			//$this->content.=$this->doc->header($LANG->getLL('newContentElement'));
			$this->content.=$this->doc->spacer(5);

			$elRow = t3lib_BEfunc::getRecordWSOL('pages',$this->id);
			$header = '<span>' . $GOBACKEND->getLL('wizardList.page.header') . '<br /></span>';
			$header .= '<img src="../../../../typo3/sysext/t3skin/icons/module_doc.gif" />';//t3lib_iconWorks::getIconImage('pages',$elRow,$BACK_PATH,' title="'.htmlspecialchars(t3lib_BEfunc::getRecordIconAltText($elRow,'pages')).'" align="top"');
			$header .= t3lib_BEfunc::getRecordTitle('pages',$elRow,1);

			$this->content.=$this->doc->section('','<h1 class="wizardListCEHeader">'.$header.'</h1>',0,1);
			//$this->content.=$this->doc->spacer(10);

				// Wizard
			$ignoreList = 'list,templavoila_pi1';
			$wizardCode='';
			$fieldName = $this->getFieldName();
			$wizardItems = $this->getWizardItems();
			$tableRows=array();
			$tableRows[] = '<tr class="wizardHeadline">
								<td colspan="2">'.$GOBACKEND->getLL('wizardList.ce.header').'</td>
							';
			if($BE_USER->isAdmin() && !in_array($wizardItem['tt_content_defValues']['CType'],explode(',', $ignoreList))){
				$tableRows[] = '
								<td><img src="../../../../typo3/sysext/t3skin/icons/module_tools_config.gif" /></td>
								<td><img src="../../../../typo3/sysext/t3skin/icons/module_web_perms.png" /></td>
							';
			}
			$tableRows[] = '</tr>';


				// Traverse items for the wizard.
				// An item is either a header or an item rendered with a title/description and icon:
			$counter=0;
			// #
			// ### Mansoor Ahmad @ Gosign media. GmbH - Set it for ...
			// #
			$stopF = 0;
			$stopP = 0;
			foreach($wizardItems as $key => $wizardItem){
				if(preg_match('/fce/', $key) === 1 && $stopF == 0){
					$stopF++;
					if ($counter>0) $tableRows[]='
						<tr>
							<td colspan="3"><br /></td>
						</tr>';
					$tableRows[]='
						<tr class="wizardHeadline">
							<td colspan="3"><strong>'.$GOBACKEND->getLL('wizardList.ff.header').'</strong></td>
						</tr>';
				}
				elseif(preg_match('/plugins/', $key) === 1 && $stopP == 0){
					$stopP++;
					if ($counter>0) $tableRows[]='
						<tr>
							<td colspan="3"><br /></td>
						</tr>';
					$tableRows[]='
						<tr class="wizardHeadline">
							<td colspan="3"><strong>'.$GOBACKEND->getLL('wizardList.pi.header').'</strong></td>
						</tr>';
				}

				if($key != 'fce' && $key != 'plugins'){
					$tableLinks=array();

						// href URI for icon/title:
					$newRecordLink = 'index.php?'.$this->linkParams().'&createNewRecord='.rawurlencode($this->parentRecord).$wizardItem['params'];

						// Icon:
					$iInfo = @getimagesize($wizardItem['icon']);
					$tableLinks[]='<a href="'.$newRecordLink.'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,$wizardItem['icon'],'').' alt="" /></a>';

						// Title + description:
					$tableLinks[]='<a href="'.$newRecordLink.'"><strong>'.htmlspecialchars($wizardItem['title']).'</strong><br />'.nl2br(htmlspecialchars(trim($wizardItem['description']))).'</a>';

						// Finally, put it together in a table row:


					// #
					// ### Mansoor Ahmad @ Gosign media. GmbH - start
					// #
					$actionForm = 'db_new_content_el.php?'.$this->linkParams().'&parentRecord='.t3lib_div::_GP('parentRecord');
					if(t3lib_div::_GP('count') == $counter && t3lib_div::_GP('go_backend_layout_edit') == 1){
						$tableRows[]='
						<tr>
							<td valign="top" colspan="3">'.
								$this->getEditTable($wizardItem, $actionForm)
							.'
								<a name="'.$wizardItem['tt_content_defValues']['CType'].'" />
							</td>
						</tr>';
					}
					else{
						$rightsChecked = '';
						$data = array('fieldName' => $fieldName, 'elementKey' => $key);
						if($this->checkRights($data)){
							$rightsChecked = 'checked="checked"';
						}

						$tableRows[]='
							<tr>
								<td valign="top"><a name="'.$wizardItem['tt_content_defValues']['CType'].'" />'.implode('</td>
								<td valign="top">',$tableLinks).'</td>
								<td valign="top">'.(($BE_USER->isAdmin() && !in_array($wizardItem['tt_content_defValues']['CType'],explode(',', $ignoreList)))?'<a href="db_new_content_el.php?'.$this->linkParams().'&parentRecord='.t3lib_div::_GP('parentRecord').'&go_backend_layout_edit=1&count='.$counter.'#'.$wizardItem['tt_content_defValues']['CType'].'"><img src="../../../../typo3/sysext/t3skin/icons/gfx/edit2.gif" /></a>':'').'</td>
								<td valign="top">'.(($BE_USER->isAdmin())?'<input type="checkbox" class="fieldrightsCheckbox" name="'.$this->getFieldName().'" value="'.$key.'" '.$rightsChecked.' />':'').'</td>
							</tr>';
						$editData = array(	'CType' => t3lib_div::_GP('CType'),
											'title'	=> t3lib_div::_GP('title'),
											'desc'	=> t3lib_div::_GP('desc'));
						if($editData['CType'] == $wizardItem['tt_content_defValues']['CType']){
							$this->saveEditTableData($editData, $wizardItem);
						}
						elseif(t3lib_div::_GP('submit')){
							header('location:'.$actionForm.'#'.t3lib_div::_GP('CType'));
						}
					}
					// #
					// ### Mansoor Ahmad @ Gosign media. GmbH - end
					// #

					$counter++;
				}

			}
				// Add the wizard table to the content:
			$wizardCode .= '<br /><br />

			<!--
				Content Element wizard table:
			-->
				<table border="0" cellpadding="1" cellspacing="2" id="typo3-ceWizardTable" style="float:left;">
					'.implode('',$tableRows).'
				</table>
				';
			$this->content .= $wizardCode;//$this->doc->section($LANG->getLL('1_selectType'), $wizardCode, 0, 1);

		} else {		// In case of no access:
			$this->content='';
			$this->content.=$this->doc->startPage($LANG->getLL('newContentElement'));
			$this->content.=$this->doc->header($LANG->getLL('newContentElement'));
			$this->content.=$this->doc->spacer(5);
		}
	}


	/*
	 * @author:	Mansoor Ahmad @ Gosign media. GmbH
	 * @description: Create a form for editing the Info of Contentelement
	*/
	function getEditTable($wizardItem, $actionForm){
		$content = '
		<form enctype="multipart/form-data" name="editceForm" action="'.$actionForm.'" method="post" >
		<table border="0" cellpadding="1" cellspacing="4" style="float:right;background:#FFFFFF;width:100%;height:auto;">
			<tr>
				<td colspan="2">
					<h3>Elementinfo bearbeiten:</h3>
				</td>
			</tr>

			<tr>
				<td>
					<span>Name:</span>
				</td>
				<td>
					<input type="text" name="title" size="25" value="'.$wizardItem['title'].'" />
				</td>
			</tr>

			<tr>
				<td>
					<span>Beschreibung:</span>
				</td>
				<td>
					<input type="text" name="desc" size="40" value="'.$wizardItem['description'].'" />
				</td>
			</tr>

			<tr>
				<td>
					<span>Wizard:</span>
				</td>
				<td>
					<input type="file" name="wizard" />
				</td>
			</tr>

			<tr>
				<td>
					<span>Pageview:</span>
				</td>
				<td>
					<input type="file" name="pageview"/>
				</td>
			</tr>

			<tr>
				<td>

				</td>
				<td>
					<input type="hidden" name="id" value="'.t3lib_div::_GP('id').'" />
					<input type="hidden" name="CType" value="'.$wizardItem['tt_content_defValues']['CType'].'" />
					<input type="hidden" name="parentRecord" value="'.t3lib_div::_GP('parentRecord').'" />
					<input type="submit" name="submit" value="Speichern"/>

				</td>
			</tr>
		</table>
		</form>

		';
		return $content;
	}



	/*
	 * @author:	Mansoor Ahmad @ Gosign media. GmbH
	 * @description: Save and manage the input of submitted form
	 */
	function saveEditTableData($editData, $wizardItem) {
		global $LANG;

		if(t3lib_extMgm::isLoaded('lfeditor')) {
			/** some needed classes and libraries */
			require_once(t3lib_extMgm::extPath('lfeditor') . 'mod1/class.tx_lfeditor_mod1_file_baseXML.php');
			require_once(t3lib_extMgm::extPath('lfeditor') . 'mod1/class.LFException.php');
			require_once(t3lib_extMgm::extPath('lfeditor') . 'mod1/class.sgLib.php');
			require_once(t3lib_extMgm::extPath('lfeditor') . 'mod1/class.typo3Lib.php');

			$fileObj = t3lib_div::makeInstance('tx_lfeditor_mod1_file_baseXML');

			if(in_array($editData['CType'], explode(',',$this->coreCTypeList))){
				$LLFName[0] = 'wizard.'.$editData['CType'].$wizardItem['tt_content_defValues']['imageorient'];
				$LLFName[1] = 'wizard.'.$editData['CType'].$wizardItem['tt_content_defValues']['imageorient'].'.description';
				$fileObj->init('locallang.xml', PATH_typo3conf . 'ext/' . 'go_backend_layout/');

				// #wizard
				$this->setImageForCType('go_backend_layout_images' ,$_FILES["wizard"]["tmp_name"], $_FILES["wizard"]["name"], 'EXT:go_backend_layout/locallang.xml', 'wizard_'.$editData['CType'].$wizardItem['tt_content_defValues']['imageorient'], 125);

				// #pageview
				$this->setImageForCType('go_backend_layout_images' ,$_FILES["pageview"]["tmp_name"], $_FILES["pageview"]["name"], 'EXT:go_backend_layout/locallang.xml', 'pageview_'.$editData['CType'], 257);
			}
			elseif($wizardItem['tt_content_defValues']['CType'] == 'templavoila_pi1'){
				// # Flexform rendering come here if needed in emergency :-), ask me for the way ... mansoor ahmad
			}
			else{
				list($LLFile, $LLFName) = each($this->myLanguageFiles[$editData['CType']]);
				$fileObj->init(basename($LLFile), str_replace('EXT:', PATH_typo3conf.'ext/', dirname($LLFile)));

				// #wizard
				$this->setImageForCType($editData['CType'] ,$_FILES["wizard"]["tmp_name"], $_FILES["wizard"]["name"], $LLFile, 'wizard', 125);

				// #pageview
				$this->setImageForCType($editData['CType'], $_FILES["pageview"]["tmp_name"], $_FILES["pageview"]["name"], $LLFile, 'pageview', 257);
			}

			$fileObj->readFile();

			if(!empty($editData['title']) && $editData['title'] != $wizardItem['title'] && !($wizardItem['tt_content_defValues']['CType'] == 'templavoila_pi1')) {
				$fileObj->setLocalLangData($LLFName[0], $editData['title'], $LANG->lang);
			}
			if(!empty($editData['desc']) && $editData['desc'] != $wizardItem['description'] && !($wizardItem['tt_content_defValues']['CType'] == 'templavoila_pi1')) {
				$fileObj->setLocalLangData($LLFName[1], $editData['desc'], $LANG->lang);
			}

			// write new language data
			try {
				$fileObj->writeFile();
			} catch(LFException $e) {
				print_r($e);
			}
		}else {echo 'NEED EXT "lfeditor"!';}
	}


	/*
	 * @author:	Mansoor Ahmad @ Gosign media. GmbH
	 * @description: Set Images in the right Contentelement dir (pi1)
	 */
	 function setImageForCType($CType, $fileTemp, $fileName, $LLFile, $fileNameOutput, $imageWidth){
		require_once(PATH_t3lib . 'class.t3lib_stdgraphic.php');
		$graphicsStd = t3lib_div::makeInstance('t3lib_stdGraphic');

		$uploadDirname = str_replace('EXT:', '', dirname($LLFile));
		$source = $fileTemp;
		$destinationUploaded = PATH_site . 'typo3temp/' . $fileName;
		t3lib_div::upload_copy_move($source, $destinationUploaded);
		$destination = t3lib_extMgm::extPath($uploadDirname) . str_replace($uploadDirname.'_','',$CType) . '/' . $fileNameOutput . '.png';

		$graphicsStd->init();
		$graphicsStd->tempPath = PATH_site . 'typo3temp/';
		$viewArray = $graphicsStd->imageMagickConvert($destinationUploaded, 'png', $imageWidth);
		rename($viewArray[3], $destination);
	 }






	/**
	 * Returns the array of elements in the wizard display.
	 * For the plugin section there is support for adding elements there from a global variable.
	 *
	 *
	 *
	 * @return	array
	 */
	function wizardArray()	{
		global $LANG,$TBE_MODULES_EXT,$TYPO3_DB,$TCA,$BE_USER;

		$fieldName = $this->getFieldName();

		$GOBACKEND = $LANG;
		$GOBACKEND->includeLLFile('EXT:go_backend_layout/locallang.xml');

		$defVals = t3lib_div::implodeArrayForUrl('defVals', is_array($this->defVals) ? $this->defVals : array());

		$wizardItems = array(
			//'common' => array('header'=>$LANG->getLL('common')),
			'header' => array(
				'icon'=>'../typo3conf/ext/go_backend_layout/images/wizard_header.png',
				'title'=>$GOBACKEND->getLL('wizard.header'),
				'description'=>$GOBACKEND->getLL('wizard.header.description'),
				'params'=>'&defVals[tt_content][CType]=header'.$defVals,
			),
			'text' => array(
				'icon'=>'../typo3conf/ext/go_backend_layout/images/wizard_text.png',
				'title'=>$GOBACKEND->getLL('wizard.text'),
				'description'=>$GOBACKEND->getLL('wizard.text.description'),
				'params'=>'&defVals[tt_content][CType]=text'.$defVals,
			),
			'textpic' => array(
				'icon'=>'../typo3conf/ext/go_backend_layout/images/wizard_textpic2.png',
				'title'=>$GOBACKEND->getLL('wizard.textpic2'),
				'description'=>$GOBACKEND->getLL('wizard.textpic2.description'),
				'params'=>'&defVals[tt_content][CType]=textpic&defVals[tt_content][imageorient]=2'.$defVals,
			),
			'image' => array(
				'icon'=>'../typo3conf/ext/go_backend_layout/images/wizard_image2.png',
				'title'=>$GOBACKEND->getLL('wizard.image2'),
				'description'=>$GOBACKEND->getLL('wizard.image2.description'),
				'params'=>'&defVals[tt_content][CType]=image&defVals[tt_content][imageorient]=2'.$defVals,
			),
			'table' => array(
				'icon'=>'../typo3conf/ext/go_backend_layout/images/wizard_table.png',
				'title'=>$GOBACKEND->getLL('wizard.table'),
				'description'=>$GOBACKEND->getLL('wizard.table.description'),
				'params'=>'&defVals[tt_content][CType]=table'.$defVals,
			),
			'bullets' => array(
				'icon'=>'../typo3conf/ext/go_backend_layout/images/wizard_bullets.png',
				'title'=>$GOBACKEND->getLL('wizard.bullets'),
				'description'=>$GOBACKEND->getLL('wizard.bullets.description'),
				'params'=>'&defVals[tt_content][CType]=bullets'.$defVals,
			),
			'html' => array(
				'icon'=>'../typo3conf/ext/go_backend_layout/images/wizard_html.png',
				'title'=>$GOBACKEND->getLL('wizard.html'),
				'description'=>$GOBACKEND->getLL('wizard.html.description'),
				'params'=>'&defVals[tt_content][CType]=html'.$defVals,
			),
		);

		// #
		// ### Mansoor Ahmad - you set here CE, which don't shown on the wizard list
		// #
		$ignoreList	= 'header,text,textpic,image,bullets,table,media,search,shortcut,div,html,list,menu,uploads,login,mailform,templavoila_pi1,dlstats_pi1,1,go_stopcslide_pi1';
		$wizardItems = $this->parseWizard($ignoreList, $wizardItems, $defVals);

		// Flexible content elements:
		$positionPid = $this->id;
		$dataStructureRecords = array();
		$storageFolderPID = $this->apiObj->getStorageFolderPid($positionPid);

		// Fetch data structures stored in the database:
		$addWhere = $this->buildRecordWhere('tx_templavoila_datastructure');
		$res = $TYPO3_DB->exec_SELECTquery(
			'*',
			'tx_templavoila_datastructure',
			'pid='.intval($storageFolderPID).' AND scope=2' . $addWhere .
				t3lib_BEfunc::deleteClause('tx_templavoila_datastructure').
				t3lib_BEfunc::versioningPlaceholderClause('tx_templavoila_datastructure')
		);
		while(FALSE !== ($row = $TYPO3_DB->sql_fetch_assoc($res))) {
			$dataStructureRecords[$row['uid']] = $row;
		}
/*
		// Fetch static data structures which are stored in XML files:
		if (is_array($GLOBALS['TBE_MODULES_EXT']['xMOD_tx_templavoila_cm1']['staticDataStructures']))	{
			foreach($GLOBALS['TBE_MODULES_EXT']['xMOD_tx_templavoila_cm1']['staticDataStructures'] as $staticDataStructureArr)	{
				$staticDataStructureArr['_STATIC'] = TRUE;
				$dataStructureRecords[$staticDataStructureArr['path']] = $staticDataStructureArr;
			}
		}
*/
		// Fetch all template object records which uare based one of the previously fetched data structures:
		$templateObjectRecords = array();
		$addWhere = $this->buildRecordWhere('tx_templavoila_tmplobj');
		$res = $TYPO3_DB->exec_SELECTquery(
			'*',
			'tx_templavoila_tmplobj',
			'pid='.intval($storageFolderPID).' AND parent=0' . $addWhere .
				t3lib_BEfunc::deleteClause('tx_templavoila_tmplobj').
				t3lib_BEfunc::versioningPlaceholderClause('tx_templavoila_tmpl'), '', 'sorting'
		);

		while(FALSE !== ($row = $TYPO3_DB->sql_fetch_assoc($res))) {
			if (is_array($dataStructureRecords[$row['datastructure']])) {
				$templateObjectRecords[] = $row;
			}
		}

		// Add the filtered set of TO entries to the wizard list:
		$wizardItems['fce']['header'] = $LANG->getLL('fce');
        foreach($templateObjectRecords as $index => $templateObjectRecord) {
            $tmpFilename = 'uploads/tx_templavoila/'.$templateObjectRecord['previewicon'];
            $wizardItems['fce_'.$index]['icon'] = (@is_file(PATH_site.$tmpFilename)) ? ('../' . $tmpFilename) : ('../' . t3lib_extMgm::siteRelPath('templavoila').'res1/default_previewicon.gif');
            $wizardItems['fce_'.$index]['description'] = $templateObjectRecord['description'] ? htmlspecialchars($templateObjectRecord['description']) : $LANG->getLL ('template_nodescriptionavailable');
            $wizardItems['fce_'.$index]['title'] = $templateObjectRecord['title'];
            $wizardItems['fce_'.$index]['params'] = '&defVals[tt_content][CType]=templavoila_pi1&defVals[tt_content][tx_templavoila_ds]='.$templateObjectRecord['datastructure'].'&defVals[tt_content][tx_templavoila_to]='.$templateObjectRecord['uid'].$defVals;
            $index ++;
        }

		// PLUG-INS:
		if (is_array($TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']))	{
			$wizardItems['plugins'] = array('header'=>$LANG->getLL('plugins'));
			reset($TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']);
			while(list($class,$path)=each($TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']))	{
				$modObj = t3lib_div::makeInstance($class);
				$wizardItems = $modObj->proc($wizardItems);
			}
		}

		// Rightsmanagment by Mansoor Ahmad
		if(!$BE_USER->isAdmin()){
			$wizardItemsTemp = $wizardItems;
			$wizardItems = array();
			foreach($wizardItemsTemp as $elementKey => $elementData){
				$data = array('fieldName' => $fieldName, 'elementKey' => $elementKey);
				if($this->checkRights($data)){
					$wizardItems[$elementKey] = $wizardItemsTemp[$elementKey];
				}
			}
		}

		// Remove elements where preset values are not allowed:
		$this->removeInvalidElements($wizardItems);
		return $wizardItems;
	}

	/**
	 * Add plugins to the wizard list
	 * @author Mansoor Ahmad - Add automaticly Plugins in the Wizardlist
	 * @author Caspar Stuebs - refactored
	 *
	 * @param string list with cTypes to be ignored
	 * @param array default wizard items, this array will be extended and given back
	 * @param string passed through default parameters
	 *
	 * @return array the new wizard item array
	 */
	function parseWizard($ignoreList, $wizardItems, $defVals) {
		$ignoreArray = explode(',', $ignoreList);
		$cTypesArray = array_keys($GLOBALS['TCA']['tt_content']['types']);
		$cTypesArray = array_diff($cTypesArray, array_keys($wizardItems), $ignoreArray);

		$cTypeConfigItems = array();
		foreach ($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] as $cTypeConfig) {
			$cTypeConfigItems[$cTypeConfig[1]] = $cTypeConfig;
		}
		foreach ($cTypesArray as $cType) {
			if (is_array($cTypeConfigItems[$cType])) {
				$wizardItems[$cType] = $this->createWizardItem($cTypeConfigItems[$cType], $defVals);
			}
		}

		return $wizardItems;
	}

	/**
	 * creates a new wizard item from configuration array
	 * @author Caspar Stuebs
	 * @author Arthur Heckmann
	 *
	 * @param array the cType configuration
	 * @param string passed through default parameters
	 *
	 * @return array the new wizard item
	 */
	protected function createWizardItem($cTypeConfigItemArray, $defVals) {
		list($piTitle, $piDesc) = $this->getLLValue($cTypeConfigItemArray[0], $cTypeConfigItemArray[1]);

		$iconFile = '';
		$filePath = $cTypeConfigItemArray[2];
		if ($filePath) {
			$pathInfo = pathinfo($filePath);
			if (strpos($pathInfo['dirname'], '../') === 0) {
				$absolutePath = PATH_site . substr($pathInfo['dirname'], 3);
			} else {
				$absolutePath = PATH_typo3 . $pathInfo['dirname'];
			}
			if (is_file($absolutePath . '/wizard.png')) {
				$iconFile = $pathInfo['dirname'] . '/wizard.png';
			} elseif (is_file($absolutePath . '/wizard.gif')) {
				$iconFile = $pathInfo['dirname'] . '/wizard.gif';
			} else {
				$iconFile = $filePath;
			}
		}

		$newWizardItem = array(
			'icon'			=>	$iconFile,
			'title'			=>	$piTitle,
			'description'	=>	$piDesc,
			'params'		=>	'&defVals[tt_content][CType]=' . $cTypeConfigItemArray[1] . $defVals
		);

		return $newWizardItem;
	}

	// #
	// ### Mansoor Ahmad - parse the LL Array
	// #
	function getLLValue($LLValue, $plugin) {
		global $LANG;

		$LLArray = explode(':', $LLValue);

		if(count($LLArray) == 4 && $LLArray[0] = 'LLL' && $LLArray[1] = 'EXT') {
			$LLFile = 'EXT:'.$LLArray[2];
			$LLFName = $LLArray[3];

			$this->myLanguageFiles[$plugin][$LLFile][] = $LLFName;
			$this->myLanguageFiles[$plugin][$LLFile][] = $LLFName.'.description';

			$locallang = $LANG;
			$locallang->includeLLFile($LLFile);
			$return = array($locallang->getLL($LLFName), $locallang->getLL($LLFName.'.description'));
		}
		else {
			$return = array($LLValue, '');
		}

		return $return;
	}

	/**
	 * Get the name of the field where the Element will be paste
	 *
	 * @author		Mansoor Ahmad
	 *
	 * @return		String		Name of the Field
	 */
	function getFieldName(){
		$parentRecord = explode(':', t3lib_div::_GP('parentRecord'));
		return $parentRecord[4];
	}

	/**
	 * Get Data from AJAX Request and insert into Databasetable gbl_fliedrights
	 *
	 * @author		Mansoor Ahmad
	 *
	 * @return		array	JSON Array of the saved data
	 */
	function saveRights(){
		$data = array();
		$data['fieldName'] = htmlspecialchars(t3lib_div::_GP('fieldName'));
		$data['elementKey'] = htmlspecialchars(t3lib_div::_GP('elementKey'));

		if(!$this->checkRights($data)){
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('gbl_fieldrights', $data);
		}
		else{
			$this->deleteRights($data);
		}

		return json_encode($data);
	}

	/**
	 * Check access right of the element
	 *
	 * @author		Mansoor Ahmad
	 *
	 * @param		array	data of fieldName and elementKey
	 *
	 * @return		boolean	in value as TRUE or FALSE
	 */
	function checkRights($data){
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'gbl_fieldrights', 'fieldName="'.$data['fieldName'].'" AND elementKey="'.$data['elementKey'].'" AND deleted="0"');
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return ($row['uid'] > 0)?TRUE:FALSE;
	}

	/**
	 * Delete the access right
	 *
	 * @author		Mansoor Ahmad
	 *
	 * @param		array	data of fieldName and elementKey
	 */
	function deleteRights($data){
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('gbl_fieldrights', 'fieldName="'.$data['fieldName'].'" AND elementKey="'.$data['elementKey'].'"', array("deleted" => "1"));
	}
}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/ux_db_new_content_el.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_backend_layout/ux_db_new_content_el.php']);
}

?>
