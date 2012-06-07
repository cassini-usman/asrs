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
 * preHeaderRenderHook for template.php
 * this hook is used to call our js-include function to add some global js
 *
 * @author		Daniel Agro <agro@gosign.de>
 * @date		2012-06-07
 *
 * @package	TYPO3
 * @subpackage	tx_gobackendlayout
 */

class tx_gobackendlayout_addHeaderFilesToBackend {

	public function addJStoBackend($hookParameters) {
			// jquery has to be forced on top
			// jqueryNoConflict has to be included first, because forced on top, it would be included before jquery...
		tx_gobackendlayout_static::addJavaScriptFile('res/javascript/jqueryNoConflict.js', 'go_backend_layout', array('forceOnTop' => TRUE));
		tx_gobackendlayout_static::addJavaScriptFile('res/javascript/jquery.js', 'go_backend_layout', array('forceOnTop' => TRUE));
	}

	public function addCSStoBackend($hookParameters){
		tx_gobackendlayout_static::addStyleSheetFile('res/css/go_backend_layout.css');
	}
}
?>