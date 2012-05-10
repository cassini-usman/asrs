<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Mansoor Ahmad (mansoor@gosign.de), Marius Stuebs (marius@gosign.de)
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
 * @author		 Marius Stuebs <marius@gosign.de>
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
 * @date 2012-01-20
 * @author Marius Stuebs <marius@gosign.de>
 */
class tx_gobackendlayout_previewContent {

	/**
	 *	The markerArray is used.
	 */
	var $markerArray = array();

	/**
	 * Sets a marker into the class markerArray.
	 * In the backend, the ### is not used at this moment.
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-23
	 *
	 * @param	String	$key
	 * @param	String	$val
	 * @return	void	nothing
	 */
	function addMarker($key, $val) {
		$this->markerArray[strtoupper($key)] = $val;
	}

	/**
	 * This function renders all default content for go_backendlayout.
	 *
	 * @param	array		$row: The row of tt_content containing the content element record.
	 * @param	array		$table	Default is 'tt_content'.
	 * @param	String		$alreadyRendered
	 * @param	Object		$self is $this for class tx_templavoila_module1 (extends t3lib_SCbase)
	 * @return	String		HTML preview content
	 *
	 * @date 2012-01-20
	 * @author Marius Stuebs <marius@gosign.de>
	 */
	function renderPreviewContent_preProcess($row, $table='tt_content', &$alreadyRendered, &$self) {
		/**
		 * @author Mansoor Ahmad <mansoor@gosign.de>
		 */

		switch($row['CType'])	{
			case 'list':		//	Insert Plugin
					switch($row['list_type']) {
						case '9':

							$html	=	$this->getTTNews($row);
							break;
						case 'rwe_feuseradmin_pi1':
							$html	=	'<strong>'.$LANG->getLL($row['list_type'].'.field.hinweis',1).'</strong>: <br />'.$LANG->getLL($row['list_type'].'.field.hinweis.content',1).'<br />'.
										$this->getPluginName($LANG->sL(t3lib_BEfunc::getLabelFromItemlist($table,'list_type',$row['list_type'])), $row['CType'], $row['uid']);
							break;
						case 'th_mailformplus_pi1':
							$html	=	'<strong>'.$LANG->getLL($row['list_type'].'.field.hinweis',1).'</strong>: <br />'.$LANG->getLL($row['list_type'].'.field.hinweis.content',1).'<br />'.
										'<br /><br /><strong style="margin:2px;padding:2px;border:1px solid #bfbfbf; background-color:#FFFFFF;">'.$LANG->sL(t3lib_BEfunc::getLabelFromItemlist($table,'list_type',$row['list_type'])).'</strong><br /><br />';
										// ELIO@GOSIGN 13/08/09 START
										// LFE-Link: we have to set the langfile like this for this plugin
										$typoscript = $self->loadTS($row['pid']);
										$langFile = $typoscript->setup['plugin.']['tx_thmailformplus_pi1.']['langFile'];
										// ELIO@GOSIGN 13/08/09 END
							break;
					}

					if($html) {
						$output = $self->link_edit($html, $table, $row['uid']).'<br />';
					}
					else {
						$output = $self->link_edit('<strong>'.$LANG->sL(t3lib_BEfunc::getItemLabel($table,'list_type')).'</strong> ' . htmlspecialchars($LANG->sL(t3lib_BEfunc::getLabelFromItemlist($table,'list_type',$row['list_type']))).' &ndash; '.htmlspecialchars($extraInfo ? $extraInfo : $row['list_type']), $table, $row['uid']).'<br />';
					}
				break;
			default:
				// Render the Rest CType Elements
				$output = $this->renderPluginDefault($row);
		}

		$alreadyRendered = TRUE;

		return $output;
	}

	/**
	 * Load the template for the specific CType.
	 * That means: First check for a template_be.html in the plugin directory.
	 * If that fails, take the default template file.
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-23
	 *
	 * @param	String		Name of the CType
	 * @return	Boolean	Success
	 */
	function loadTemplate($CType) {
		$this->doc = t3lib_div::makeInstance('template');

		$extensionNameParts = explode('_', $CType);
		$pluginName = array_pop($extensionNameParts);
		$extensionName = implode('_', $extensionNameParts);

			// ATTENTION: NOT ALL EXTENSIONNAMES are from CTYPEs
			// e.g. text as a CType has not affiliated Extension
		if (t3lib_extMgm::isLoaded($extensionName)) {
			$extTemplatePath = t3lib_extMgm::extPath($extensionName) . $pluginName . '/template_be.html';
		}
		if (!file_exists($extTemplatePath)) {
			$extTemplatePath = t3lib_extMgm::extPath('go_backend_layout') . 'moduls/templavoila/template_be.html';
		}
		return $this->doc->setModuleTemplate($extTemplatePath);
	}

	/**
	 * render Plugin in the Backendlistview
	 * @author Mansoor Ahmad - render Pi in the Backendlistview
	 * @author Caspar Stuebs, Arthur Heckmann - refactored, fixed bug with new structure
	 *
	 * @param array the row of the current Element
	 *
	 * @return string the HTML output
	 */
	function renderPluginDefault($row) {
			// set default value markers
			// that can be used even if everything else fails.
		$this->addMarker('ROW_CTYPE', htmlspecialchars($row['CType']));
		$this->addMarker('PLUGIN_NAME', htmlspecialchars($row['CType']));
		$this->addMarker('ROW_UID', (int)$row['uid']);

		/*
		 * Check if the content element has showItem informations.
		 * If not, use the default showItem informations that are set by default or
		 * are set for ALL CTypes ... e.g. "Access" or "go_image_cropping".
		 * Normally though, it is only CType and nothing more.
		 */
		$CType = $row['CType'];
		if (!array_key_exists($CType, $GLOBALS['TCA']['tt_content']['types'])) {
			$CType = 1;
		}
		$showItem = $GLOBALS['TCA']['tt_content']['types'][$CType]['showitem'];
		/*
		 * Search the cTypeConfig.
		 * @TODO What is this?
		 */
		if (is_array($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'])) {
			foreach ($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] as $cTypeConfig) {
				if ($cTypeConfig[1] == $row['CType']) {
					/*
					 * If there are enough TCA informations,
					 * Render the backend preview.
					 */
					$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $GLOBALS['BE_USER']->getPagePermsClause(1));
					// is $this->pageinfo always FALSE?
					//if ($this->pageinfo ) {
						$this->addMarker('PLUGIN_NAME', $this->getLLValue($cTypeConfig[0]));
						$this->loadTemplate($row['CType']);
						$alreadyDisplayed = $this->getAlreadyDisplayedFields($this->pageinfo);
						$output = $this->getCSS($row['CType'], $this->getPageviewImage($cTypeConfig[2]));

						$showItem = $this->explodeShowItem($showItem, 'tt_content');
						$this->setParsedFieldMarkerArray($showItem, $row);


						$showItem = $this->removeFromShowItem($showItem, $alreadyDisplayed);

						$markerSArray = $this->setSubpartIfMarker();

						$output .= $this->doc->moduleBody($this->pageinfo, array(), $this->markerArray, $markerSArray);
						$output .= $this->getParsedFields($showItem, $row);

					//} else {
					//	$output = $this->getCSS($row['CType'], $this->getPageviewImage($cTypeConfig[2]));
					//	$output .= '<strong>You don&lsquo;t have permisson to access this CE.';
					//}
					break;
				}
			}
		}

		return $output;
	}

	function explodeShowItem($showItem, $table) {
		$showItemNew = array();
		foreach(t3lib_div::trimExplode(',', $showItem) as $v) {
			list($field,$label,$palettes,$config,$style) = t3lib_div::trimExplode(';',$v);

			$showItemNew[] = implode(';', array($field,$label,'',$config,$style));
			if ($palettes) {
				$showItemNew[] = $GLOBALS['TCA'][$table]['palettes'][$palettes]['showitem'];
			}

		}
		return implode(',', $showItemNew);
	}


	function setSubpartIfMarker() {
		$html = $this->doc->moduleBody($this->pageinfo, array(), $this->markerArray);
		$matches = array();

		$resultCount = preg_match_all('/###SUBPART_IF:([^#]+)###/', $html, $matches, PREG_PATTERN_ORDER);
		$markerSArray = array();
		if ($resultCount !== FALSE) {
			foreach ($matches[1] as $subpartMarkerIf) {
				if (!trim($this->markerArray[$subpartMarkerIf])) {
					$markerSArray['SUBPART_IF:' . $subpartMarkerIf] = '';
				}
			}
		}

		return $markerSArray;
	}


	/**
	 * Search the template for all used markers
	 * and return a list of them
	 *
	 * Special: If there is a marker HIDE:MARKER
	 * then the marker is taken as used, even though
	 * the marker itself is not replaced.
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-25
	 *
	 * @param	dunno	$pageinfo is needed to load the template
	 * @return	Array	All used and hidden markers
	 */
	function getAlreadyDisplayedFields($pageinfo) {
		$html = $this->doc->moduleBody($pageinfo);
		$matches = array();
		preg_match_all('/###([^#]+)###/', $html, $matches);

		foreach ($matches[1] as $match) {
			if (strpos($match, 'HIDE:') === 0) {
				$matches[1][] = substr($match, 5);
				$this->addMarker($match, '');
			}
		}
		return $matches[1];
	}

	/**
	 * The showItem String is used to render
	 * all database fields.
	 *
	 * Some fields have to be removed, if they are
	 * -> getAlreadyDisplayedFields();
	 * already displayed fields.
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-25
	 *
	 * @param	String	$showItem Comma separated List of database fields and configuration
	 * @param	Array	$needles Array of Strings that are to removed.
	 * @return	String	$showItem without $needles and without spaces, tabs and newlines.
	 */
	function removeFromShowItem($showItem, $needles) {
		$showItem = preg_replace("/\n|\t|\s/", '', $showItem);
		foreach ($needles as $val) {
			$showItem = preg_replace('/,'.strtolower($val).'[^,]*,/i', ',', $showItem);
		}
		return $showItem;
	}


	/**
	 * @author	Mansoor Ahmad
	 * @company Gosign media. GmbH
	 *
	 * @param	array	Current Input of the tt_content Table
	 *
	 * @author	Lucas Jenß <lucas@gosign.de>
	 *
	 * @return	String	HTML output for the tt_news plugin
	 */
	function getTTNews($row){
		global $GBL, $TCA;

		$flexArrayTTNews = t3lib_div::xml2array($row['pi_flexform']);
		$displayTyp = $flexArrayTTNews['data']['sDEF']['lDEF']['what_to_display']['vDEF'];

		if($displayTyp == 'LIST'){
			//# Make Instance
			require_once(PATH_typo3 . 'class.db_list_extra.inc');
			$dbList = t3lib_div::makeInstance('localRecordList');
			//require_once(t3lib_extMgm::extPath('tt_news').'lib/class.tx_ttnews_recordlist.php');
			//$dbList = t3lib_div::makeInstance('tx_ttnews_recordlist');

			//# Set Attribute
			$dbList->tableList = 'tt_news';
			$dbList->showLimit = ($flexArrayTTNews['data']['s_template']['lDEF']['listLimit']['vDEF'])?$flexArrayTTNews['data']['s_template']['lDEF']['listLimit']['vDEF']:10;
			$dbList->id = $flexArrayTTNews['data']['s_misc']['lDEF']['pages']['vDEF'];
			$dbList->table = 'tt_news';
			$dbList->setFields = array('uid','title');
			$dbList->noControlPanels = FALSE;
			$dbList->newWizards = FALSE;
			$dbList->displayFields = array('uid','title');
			$dbList->localizationView = TRUE;
			$dbList->allFields = 0;
			$dbList->thumbs = 1;
			$dbList->calcPerms = 16;
			$dbList->fieldArray = array('_LOCALIZATION_');

			//# Fill the $GBL
			$archiv = ($flexArrayTTNews['data']['sDEF']['lDEF']['archive']['vDEF'] == 1) ? 'archivdate > datetime' : '';

			$uidList = '0';
			$uidCatList = $flexArrayTTNews['data']['sDEF']['lDEF']['categorySelection']['vDEF'];
			//$titleCatList = '';


			$resTTNews = $GLOBALS['TYPO3_DB']->exec_SELECTquery('d.uid, c.title', 'tt_news AS d INNER JOIN tt_news_cat_mm AS cm ON d.uid = cm.uid_local INNER JOIN tt_news_cat AS c ON c.uid = cm.uid_foreign LEFT JOIN tt_news_related_mm AS m ON d.uid = m.uid_local', 'd.pid IN(" '. $dbList->id .'",126) AND cm.uid_foreign IN("'.$uidCatList.'") ',  '',  '');
			while($rowTTNews	=	$GLOBALS['TYPO3_DB']->sql_fetch_assoc($resTTNews)) {
				$uidList .=  ','.$rowTTNews['uid'];
				//$titleCatList .=  $rowTTNews['title'] . ', ';
			}


			$GBL['tt_news.']['uidList'] = $uidList;

			//print_r($GBL);


			$dbList->start($dbList->id,$dbList->table,0,$dbList->search_field,'-1',$dbList->showLimit);

			$dbList->sortField = ($flexArrayTTNews['data']['sDEF']['lDEF']['listOrderBy']['vDEF'])?$flexArrayTTNews['data']['sDEF']['lDEF']['listOrderBy']['vDEF']:'sorting';
			$dbList->sortRev = ($flexArrayTTNews['data']['sDEF']['lDEF']['ascDesc']['vDEF'] == 'desc')?TRUE:FALSE;

			$dbList->generateList();

			//$buttons = $dbList->getButtons();

			$dbList->backPath = '../../../../typo3/';

			$output = '	<div class="list_options_header">Plugin Einstellungen</div>
						<div class="list_options">
							<span>Darstellungsart:</span> Listenansicht
							<br />
							<span>Anzahl der Nachrichten pro Seite:</span> '.$flexArrayTTNews['data']['s_template']['lDEF']['listLimit']['vDEF'].'
							<br />
							<span>Sotiert nach:</span> '.$dbList->sortField.'
							<br />
							<span>Sortierreihenfolge:</span> '.((!$dbList->sortRev)?'Aufsteigend':'Absteigend').'
							<br />
							<span>Nachrichtenordner:</span> '.$this->getPagename($flexArrayTTNews['data']['s_misc']['lDEF']['pages']['vDEF']).'
						</div>';

			$output .= $dbList->getTable('tt_news',$dbList->id,'title,bodytext,datetime,archivedate,_LOCALIZATION_');
			$output = str_replace('typo3/gfx/','../../../typo3/gfx/',$output);
			$output = str_replace('href="tce_db.php','href="../../../../typo3/tce_db.php',$output);
			$output = str_replace('db_list.php?id=','../../../../typo3/db_list.php?id=',str_replace('showLimit=2','showLimit=500',$output));
		}
		else {
			$output = $this->getPluginName('tt-News', $row['CType'], $row['uid']);
		}
		return $output;
	}


	// #
	// ### Mansoor Ahmad - Formate the Pagelink, if is a one, otherweise get you the rowcontent
	// #
	// # Refactored by marius <marius@gosign.de>
	// # @date 2012-01-26
	// # oh so less time, so much to do ...
	static function getPagename($id) {
		if (is_numeric($id) && $id > 0){
			$query	=	$GLOBALS['TYPO3_DB']->exec_SELECTquery('title', 'pages', 'uid='.$id);
			$res	=	$GLOBALS['TYPO3_DB']->sql_fetch_assoc($query);
			$output	=	$res['title'].' (ID: '.$id.')';
		}
		else{
			$output = $id; //str_replace('/',' / ',$id);
			if(strlen($id) > 80){
				$output = substr($output, 0, 80) . '...';
			}
		}
		return $output;
	}



	// #
	// ### Mansoor Ahmad - convert Image to an Icon for the Backendpreview
	// #
	// # Refactored by marius <marius@gosign.de>
	// # @date 2012-01-26
	// # oh so less time, so much to do ...
	function getThumbNail($imagerow, $filepath = '../uploads/pics/') {
		//$placeForTemplavoilaTempThumbnails =

		$output = '';
		$BE_func = new t3lib_BEfunc();
		foreach(explode(',',$imagerow) as $src) {
			if(file_exists(PATH_typo3conf . $filepath . $src)) {
				$imgFile = PATH_typo3conf . $filepath . $src;
				$data = str_replace(urlencode(PATH_typo3conf), '',str_replace(PATH_typo3, t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3/', $BE_func->getThumbNail(PATH_typo3.'thumbs.php', $imgFile,'','100x100')));
				$output .= str_replace('<img', '<img style="margin:2px 2px 2px 1px;border:1px solid;"', $data);
			}
		}
		return  $output;
	}



	// ### Mansoor Ahmad - support DAM image_field
	// #
	/**
	 *
	 *
	 * @param String	$key; Name of the database field for DAM Images
	 * @param int  	uid of the tt_content row
	 */
	function getThumbNail_DAM($key, $content_row) {
		global $TCA;
		$tt_content_uid = $content_row['uid'];
		//	var_dump($key); die();

		$images = '';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'd.file_path, d.file_name',
			'tx_dam AS d INNER JOIN tx_dam_mm_ref AS m ON d.uid = m.uid_local',
			'm.uid_foreign = '.$tt_content_uid.' AND m.ident = \''.$key .'\'',
			'',
			'sorting_foreign');

		// If it is a DAM field ...
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				if($this->isImage($row['file_name'])) {
					$images .= $this->getThumbNail($row['file_name'],'../'.$row['file_path']);
				} elseif ($row['file_path']) {
						// If this is NO image field (as TCA says), just print out the file name.
						// If it is still a file
					$images .= $this->getPagename($row['file_path'] . $row['file_name']) . '<hr />,';
				}
			}
		} else {
			// If it is NO DAM field
			$images = $content_row[$key];
		}
		return $images;
	}

	/** Has the filename a known Image ending?
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-23
	 *
	 * @param	String 	Filename
	 * @return	Boolean	is image?
	 */
	function isImage($filename) {


		$pathinfo = pathinfo($filename);
		$ext = strtolower($pathinfo['extension']);

		$imageEndings = 'jpeg,jpg,png,gif,ico,svg';
		$ret = FALSE;
		if (in_array($ext, explode(',', $imageEndings))) {
			$ret = TRUE;

		}
		return $ret;
	}

	/**
	 * get CSS for the BG Image (pageview) for a plugin
	 * @author Mansoor Ahmad
	 * @author Caspar Stuebs, Arthur Heckmann
	 *
	 * @param string the plugin name
	 * @param string the relative path to the image
	 *
	 * @return string CSS output
	 */
	function getCSS($pluginName, $imageFilePath) {
		$imgRelPath = '../../../' . $imageFilePath;
		$imgAbsPath = $this->getAbsolutePath($imageFilePath);

		if($pluginName && file_exists($imgAbsPath)) {
			$imgInfo = getimagesize($imgAbsPath);
			$output	=	'<style type="text/css">
							td.templavoila_pi1 td.templavoila_pi1 td.'.$pluginName.':hover,
							td.templavoila_pi1 td.'.$pluginName.':hover,
							td.'.$pluginName.':hover,
							td.'.$pluginName.' {
								background:url(\''.$imgRelPath.'\');
								background-repeat:no-repeat;
								background-position:right center;
								vertical-align:bottom;
								padding-left:2px;
							}
						</style>';

			return $output;
		}
	}


	/**
	 * returns the relative pageview image path
	 * @author Caspar Stuebs, Arthur Heckmann
	 *
	 * @param string the path of the default plugin icon
	 *
	 * @return string relative path
	 */
	protected function getPageviewImage($filePath) {
		$pageviewFile = '';

		if ($filePath) {
			$pathInfo = pathinfo($filePath);
			$absolutePath = $this->getAbsolutePath($pathInfo['dirname']);
			if (is_file($absolutePath . '/pageview.png')) {
				$pageviewFile = $pathInfo['dirname'] . '/pageview.png';
			} elseif (is_file($absolutePath . '/pageview.gif')) {
				$pageviewFile = $pathInfo['dirname'] . '/pageview.gif';
			}
		}

		return $pageviewFile;
	}

	/**
	 * returns the absolute path of an imagepath
	 * @author Caspar Stuebs, Arthur Heckmann
	 *
	 * @param string the relative path
	 *
	 * @return string the absolute path
	 */
	protected function getAbsolutePath($filePath) {
		$absolutePath = '';

		if ($filePath) {
			if (strpos($filePath, '../') === 0) {
				$absolutePath = PATH_site . substr($filePath, 3);
			} else {
				$absolutePath = PATH_typo3 . $filePath;
			}
		}

		return $absolutePath;
	}

	// #
	// ### Mansoor Ahmad - render TV Flexform in the Backendlistview
	// #
	function renderFlex($row) {
		global $TCA;

		$flexData	= t3lib_div::xml2array($row['tx_templavoila_flex']);
		$resDS	= $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", "tx_templavoila_datastructure", "uid ='" . $row['tx_templavoila_ds'] . "'", "", "sorting");
		$output = '';
		$typeCE = '';
		while($rowDS	=	$GLOBALS['TYPO3_DB']->sql_fetch_assoc($resDS)) {
			$flexDataDS = t3lib_div::xml2array($rowDS['dataprot']);
			$v = 'flex_'.$row['tx_templavoila_to'];

			if(is_array($flexData) && is_array($flexDataDS)) {
				foreach($flexData['data']['sDEF']['lDEF'] as $fieldname => $fieldarray) {
					//print_r($flexDataDS['ROOT']['el'][$fieldname]['tx_templavoila']['eType']);
					if($flexDataDS['ROOT']['el'][$fieldname]['tx_templavoila']['eType'] != 'ce' && !empty($flexData['data']['sDEF']['lDEF'][$fieldname])) {

						$key = $flexDataDS['ROOT']['el'][$fieldname]['TCEforms']['config']['type'];
						$TCA['tt_content']['columns'] = array_merge($TCA['tt_content']['columns'],array( $v => $flexDataDS['ROOT']['el'][$fieldname]['TCEforms']));
						foreach($flexData['data']['sDEF']['lDEF'][$fieldname] as $langKey => $langValue) {
							switch($langKey) {
								case (($langValue)?'vEN':'0'):
									$wrap = '<table class="trans" width="33%" style="float:left;"><tr><td valign="top" style="width:20px"><br /><img title="English" alt="English" src="../../../../typo3/gfx/flags/gb.gif"/></td><td valign="top">|</td></tr></table>';
									break;
								default:
									$wrap = '|';
									break;
							}
							$wrap = explode ('|',trim($wrap));

							$row[$v] = $langValue;
							$output .= $wrap[0] . $this->getWrappedField($key,$v,$row,0) . $wrap[1];
						}
					}
					elseif($flexDataDS['ROOT']['el'][$fieldname]['tx_templavoila']['eType'] == 'ce') {
						$typeCE = 'ce';
					}
				}
				unset($TCA['tt_content']['columns'][$v]);
				$output	.=	$this->getPiName($rowDS['title'], $typeCE, $row['uid']);
			}
		}

		return $output;
	}

	// #
	// ### Mansoor Ahmad - Split and arrange Fields, which gets from the $TCA
	// #
	/**
	 *
	 * @TODO <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;border:0px solid #CCCCCC;padding-left:2px;">
	 *       Needs really much space. That must be smarter/smaller.
	 * @comment by Marius Stuebs <marius@gosign.de>
	 */
	function getParsedFields($items, $row) {
		global $TCA;

		$output = '';

		// get TSconfig
		$TSconfig = t3lib_BEfunc::getPagesTSconfig(3,$rootLine='',$returnPartArray=0);
		$TCEFORM	=	$TSconfig['TCEFORM.']['tt_content.'];
		$border = 0;
		foreach(t3lib_div::trimExplode(',', $items) as $v) {
			list($field,$label,$palettes,$config,$style) = t3lib_div::trimExplode(';',$v);

			if(!empty($field) && $field != 'CType') {
				// # Level1
				$key = $TCA['tt_content']['columns'][$field]['config']['type'];


				list($wrappedFieldHeader, $wrappedFieldValue) = $this->getWrappedField($key, $field, $row, $TCEFORM, $label);
				if ($wrappedFieldValue) {
					$output .= '<table cellpadding="0" cellspacing="0" border="0" style="border:0px solid #CCCCCC;padding-left:2px;">';
					if($style && $border > 0) {
						$output .= '<tr><td style="border-bottom:1px solid #CCCCCC;" colspan="2">&nbsp;</td></tr>';
					}
					$output .= '<tr><th style="padding-right: 10px;">';
					$output .= $wrappedFieldHeader . '</th><td>' . $wrappedFieldValue;
					$border = ($wrappedFieldValue)?1:0;
					$output .= '</td></tr>';

					// # Level 2
					if($palettes) {
						$output .= '<tr>';
						$Items2 = $TCA['tt_content']['palettes'][$palettes]['showitem'];
						foreach(t3lib_div::trimExplode(',', $Items2) as $v2){
							$output .= '<th style="border-bottom:0px solid #CCCCCC;">';
							list($field2,$label2,$plattes2,$config2,$style2) = t3lib_div::trimExplode(';',$v2);
							$key2 = $TCA['tt_content']['columns'][$field2]['config']['type'];
							list($wrappedFieldHeader2, $wrappedFieldValue2) =  $this->getWrappedField($key2, $field2, $row, $TCEFORM, $label2);
							$output .= $wrappedFieldHeader2 . ':</th><td style="border-bottom:0px solid #CCCCCC;">' . $wrappedFieldValue2;
							$border = ($wrappedFieldValue2)?2:$border;
							$output .= '</td>';
						}
						$output .= '</tr>';
					}
					$output .= '</table>';
				}
			}
		}
		return $output;
	}

	/**
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @date 2012-01-23
	 */
	function setParsedFieldMarkerArray($items, $row, $table='tt_content') {
		global $TCA;

		// get TSconfig
		$TSconfig = t3lib_BEfunc::getPagesTSconfig(3,$rootLine='',$returnPartArray=0);
		$TCEFORM	=	$TSconfig['TCEFORM.'][$table . '.'];
		foreach(t3lib_div::trimExplode(',', $items) as $v) {
			list($field,$label,$palettes,$config,$style) = t3lib_div::trimExplode(';',$v);
			if(!empty($field) && $field != 'CType') {
				// # Level1
				$key = $TCA[$table]['columns'][$field]['config']['type'];
				list($wrappedFieldHeader, $wrappedFieldValue) = $this->getWrappedField($key, $field, $row, $TCEFORM, $label);
				$this->addMarker($field, $wrappedFieldValue);
			}
			/**
			 * If there is a palette to this specific item,
			 * render it like it would be an item itself.
			 *
			 * @author Marius Zirngibl <mariusz@gosign.de>
			 * @date 2012-02-27
			 */
			if ($palettes) {
				$showItemString = $GLOBALS['TCA'][$table]['palettes'][$palettes]['showitem'];
				$this->setParsedFieldMarkerArray($showItemString, $row, $table);
			}
		}
	}


	// #
	// ### Mansoor Ahmad - return formated and wrapped Fieldstypes
	// #
	/**
	 *
	 * @author Mansoor Ahmad
	 *
	 * @TODO Documentation!
	 * @TODO What about other *input* fields than Date and Pagenumber?
	 *
	 * @TODO Switch rendering for types like text->text, text->html, text->rte_text.
	 *
	 */
	function getWrappedField($key, $v, $row, $TCEFORM, $LLName='', $table='tt_content') {
		global $TCA;
		//echo $v.'<br/>';
		$wrappedFieldHeader = '';
		$wrappedFieldValue = '';
		$ignoreList = 'colPos,section_frame,sys_language_uid,l18n_parent,module_sys_dmail_category';
		if(in_array($v,explode(',',$ignoreList)) === FALSE) {
			($TCA[$table]['columns'][$v]['config']['internal_type'] == 'file' && $TCA[$table]['columns'][$v]['config']['show_thumbs'] == '1') ? $key = 'image' : '' ;
			//echo $v . '--' . $row[$v] . '<br /><br />';
			$label = $this->getLLValue((($LLName)?$LLName:$TCA[$table]['columns'][$v]['label']), $v, $TCEFORM);
			if (!$label) {
				$label = '[' . $v . ']';
			}
			switch($key) {
				case 'input':
					if (strlen($row[$v]) > 9 && is_numeric($row[$v])) {
						$wrappedFieldValue = date("d.m.Y",$row[$v]);
					} else {
						if ($TCA[$table]['columns'][$v]['config']['foreign_table'] == 'pages') {
							$wrappedFieldValue = $this->getPagename($row[$v]);
						}
						else {
							$wrappedFieldValue = $row[$v];
						}
					}
					break;
				case 'text':
						// replace only paths to local images
						// not those with HTTP: in front of.
						// @author Marius Stuebs <marius@gosign.de>
					$wrappedFieldValue = preg_replace('/src="(?!https?:)/i','src="../../../../', $row[$v]);
					break;
				case 'image':
					$wrappedFieldValue = $this->getThumbNail_DAM($v, $row);
					break;
				case 'select':
					$selectItems = is_array($TCA[$table]['columns'][$v]['config']['items']) ? $TCA[$table]['columns'][$v]['config']['items'] : array();
					foreach($selectItems as $sk => $sv) {
						($TCA[$table]['columns'][$v]['config']['items'][$sk]['1'] == $row[$v]) ? $selectValue = $TCA[$table]['columns'][$v]['config']['items'][$sk]['0']:'';
					}
					$wrappedFieldValue = $this->getLLValue($selectValue, $v, $TCEFORM);
					break;
				case 'group':
					if ($TCA[$table]['columns'][$v]['config']['allowed'] == 'pages') {
						$wrappedFieldValue = $this->getPagename($row[$v]);
					} else {
						$wrappedFieldValue = str_replace(',','<br />',$this->getThumbNail_DAM($v,$row));
					}
					break;
				default:
					break;
			}
		}

		$label = htmlspecialchars($label);
		return array($label, $wrappedFieldValue);
	}


	// #
	// ### Mansoor Ahmad - parse the LL Array
	// #
	function getLLValue($LLValue, $type = '', $TCEFORM = array('')) {
		global $LANG;

		// parse Labels over TSconfig
		$TSaltLabel =  $TCEFORM[$type.'.']['altLabels.'][trim(substr(strrchr($LLValue,'.')+1,2))];
		$TSLabel = ($TSaltLabel)?$TSaltLabel:$TCEFORM[$type.'.']['label'];
		$LLValue = (strpos($TSLabel, '.xml') || strpos($TSLabel, '.xml'))?$LLValue = $TSLabel:$LLValue;
		$TSLabel = ($TSLabel == $LLValue)?$TSLabel = '':$TSLabel;

		if(strpos($LLValue, '.xml')) {
			$LLFile		=	strstr(substr($LLValue, 0, strrpos($LLValue, '.xml:')), 'EXT:') . '.xml';
			$LLFName	=	str_replace('.xml:', '', strstr($LLValue, '.xml:'));
		}
		else {
			$LLFile		=	strstr(substr($LLValue, 0, strrpos($LLValue, '.php:')), 'EXT:') . '.php';
			$LLFName	=	str_replace('.php:', '', strstr($LLValue, '.php:'));
		}

		if(strlen($LLFile) > 4 && empty($TSLabel)) {
			$locallang = $LANG;
			$locallang->includeLLFile($LLFile);
			return $locallang->getLL($LLFName);
		}
		elseif($TSLabel) {
			return $TSLabel;
		}
		else {
			return $LLValue;
		}
	}
}
?>