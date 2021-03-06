<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Caspar Stuebs <caspar@gosign.de>
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
 * Gosign Pluginbase, Project specific.
 *
 * @author	Caspar Stuebs <caspar@gosign.de>
 */

class tx_gopibaselocal {
	protected $pObj;	//The parent object, to access the (go_)pibase functions

	/*
	 * Class constructor.
	 */
	function __construct(&$pObj) {
		$this->pObj = $pObj;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_pibase_local/lib/class.tx_gopibaselocal.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_pibase_local/lib/class.tx_gopibaselocal.php']);
}

?>
