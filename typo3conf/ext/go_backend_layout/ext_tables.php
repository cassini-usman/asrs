<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=='BE')	{

	// Setting the relative path to the extension in temp. variable:
	$temp_eP = t3lib_extMgm::extRelPath($_EXTKEY);

	$TBE_STYLES['stylesheet2'] = $temp_eP.'go_backend_layout.css';

	t3lib_div::loadTCA('be_users');
	$TCA['be_users']['columns']['lang']['config']['default'] = 'de';

	//t3lib_extMgm::addModule('user', 'gobeconfig', '', t3lib_extMgm::extPath($_EXTKEY) . 'moduls/config/');



	/**
	 * Zusätzlicher Seitentyp: Page Container. doktype 21.
	 *
	 *
	 * @author Marius Stuebs <marius@gosign.de>
	 * @date 2011-07-21
	 */
	t3lib_div::loadTCA("pages");
	t3lib_SpriteManager::addTcaTypeIcon('pages', '21', '../typo3conf/ext/go_backend_layout/res/doktype_icon_pagecontainer.png');
	// an der richtigen Stelle den neuen Seitentyp einfügen.
	$ersteHaelfte = array_slice($TCA['pages']['columns']['doktype']['config']['items'], 0, 9);
	$zwoteHaelfte = array_slice($TCA['pages']['columns']['doktype']['config']['items'], 9);
	$neuePagetypes = $ersteHaelfte;
	$neuePagetypes[] = array('LLL:EXT:go_backend_layout/locallang.xml:pages.doktypes.pagecontainer', '21','EXT:go_backend_layout/res/doktype_icon_pagecontainer.png');
	$neuePagetypes = array_merge($neuePagetypes, $zwoteHaelfte);
	$TCA['pages']['columns']['doktype']['config']['items'] = $neuePagetypes;
	// Einstellungsmöglichkeiten werden vom SysOrdner (doktype 3) übernommen.
	$TCA['pages']['types'][21]['showitem'] = $TCA['pages']['types'][254]['showitem'];

}
?>