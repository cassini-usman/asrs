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
 * @author	Daniel Agro <agro@gosign.de>
 * @date	2012-07-25
 *
 * This class contains two hook functions which are called in the page module
 * by templavoila at the very top of the content in the main frame.
 *
 */
class tx_gobackendlayout_pageModulePreContent {

	/**
	 * This function handles the the incoming ajax requests in the page module
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date	2012-07-26
	 *
	 * @param	string	$emptyArray: templavoila delivers an empty array...
	 * @param	string	$tvParentObject: the parent object which calls this hook
	 *
	 * @return	void
	 */
	public function handleAjaxRequest($emptyArray, $tvParentObject){
		if (tx_gobackendlayout_static::notesFunctionalityEnabled()) {
				// include stickynotes css
			tx_gobackendlayout_static::addStyleSheetFile('res/css/stickyNotes.css');
				// include stickynotes lib and the jQueryUi lib
			tx_gobackendlayout_static::addJavaScriptFile('res/javascript/jQuery.stickynotes.js', 'go_backend_layout');
			tx_gobackendlayout_static::addJavaScriptFile('res/javascript/jQueryUi.js', 'go_backend_layout');
				// include the js which builds the stickynotes object
			tx_gobackendlayout_static::addJavaScriptFile('res/javascript/notes.js', 'go_backend_layout');

			$this->postData = array_merge(t3lib_div::_GET(), t3lib_div::_POST());
			if($this->postData['isAjax']) {
				switch($this->postData['action']) {
					case 'createNote':
						tx_gobackendlayout_static::createNote($this->postData);
						break;
					case 'editNote':
						$updateFields = array('text' => $this->postData['noteText']);
						tx_gobackendlayout_static::updateNote($this->postData['noteId'], $this->postData['id'], $updateFields);
						break;
					case 'deleteNote':
						tx_gobackendlayout_static::deleteNote($this->postData['noteId'], $this->postData['id'], $this->postData['id']);
						break;
					case 'resizeNote':
						$updateFields = array('width' => $this->postData['noteWidth'], 'height' => $this->postData['noteHeight']);
						tx_gobackendlayout_static::updateNote($this->postData['noteId'], $this->postData['id'], $updateFields);
						break;
					case 'moveNote':
						$updateFields = array('posX' => $this->postData['notePosX'], 'posY' => $this->postData['notePosY']);
						$note = tx_gobackendlayout_static::updateNote($this->postData['noteId'], $this->postData['id'], $updateFields);
						break;
				}
				die();
			}
		}
	}

	/**
	 * This function fetches the notes from the 'tx_gobackendlayout_notes' table
	 * and builds hidden input fields for the javascript. The return value is placed in the
	 * page module by templavoila at the very top of the content in the main frame.
	 *
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @date	2012-07-25
	 *
	 * @param	string	$emptyArray: templavoila delivers an empty array...
	 * @param	string	$tvParentObject: the parent object which calls this hook
	 *
	 * @return	string	html string with the hidden infos
	 */
	public function addHiddenNotes($emptyArray, $tvParentObject){
		if (tx_gobackendlayout_static::notesFunctionalityEnabled()) {
			$mainWrap = array('<div id="notes" style="display:none;position:absolute;z-index:99999999;width:100%;height:100%;">', '</div>');
			$singleNoteWrap = array('<div class="singleNote id_', '">', '</div>');
			$hiddenInfoWrap = array('<input type="hidden" class="', '"value="', '" />');
			$ignoreFields = array('uid', 'pageId', 'deleted');
			$hiddenInfo = '';

			$notes = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'tx_gobackendlayout_notes', 'pageId = "' . t3lib_div::_GP('id') . '" AND deleted = 0', '', '', '', '');

			if (!empty($notes)) {
				foreach ($notes as $singleNote){
					if (empty($singleNote)) {
						continue;
					}
					$singleHidden = '';
					foreach ($singleNote as $singleInfoKey => $singleInfoValue) {
						if (in_array($singleInfoKey, $ignoreFields)) {
							continue;
						}
						$singleHidden .= $hiddenInfoWrap[0] . $singleInfoKey . $hiddenInfoWrap[1] . $singleInfoValue . $hiddenInfoWrap[2];
					}
					$hiddenInfo .= $singleNoteWrap[0] . $singleNote['noteId'] . $singleNoteWrap[1] . $singleHidden . $singleNoteWrap[2];
				}
			}

			return $mainWrap[0] . $hiddenInfo . $mainWrap[1];
		}
	}
}
?>