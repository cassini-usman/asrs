<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Caspar Stuebs <caspar@gosign.de>
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
 * @author Caspar Stuebs <caspar@gosign.de>
 *
 * @description autoload go_backend_layout PHP files
 */

$go_backend_layoutClasses = array(
	'tx_gobackendlayout_addheaderfilestobackend' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_addHeaderFilesToBackend.php',
	'tx_gobackendlayout_previewcontent' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_previewContent.php',
	'tx_gobackendlayout_manipulatewizarditems' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_manipulateWizardItems.php',
	'tx_gobackendlayout_ajaxrequests' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_ajaxRequests.php',
	'tx_gobackendlayout_static' => t3lib_extMgm::extPath('go_backend_layout') . 'lib/class.tx_gobackendlayout_static.php',
	'tx_gobackendlayout_getprocessedvalue' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_getProcessedValue.php',
	'tx_gobackendlayout_pagemoduleprecontent' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_pageModulePreContent.php',
	'tx_gobackendlayout_userauthgroup' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_userAuthGroup.php',
	'tx_gobackendlayout_handleincomingcommands' => t3lib_extMgm::extPath('go_backend_layout') . 'hooks/class.tx_gobackendlayout_handleIncomingCommands.php',
);

return $go_backend_layoutClasses;
?>