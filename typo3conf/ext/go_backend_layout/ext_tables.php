<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=='BE')	{
	// set default language for backend users to 'de'
	t3lib_div::loadTCA('be_users');
	$TCA['be_users']['columns']['lang']['config']['default'] = 'de';
}

/**
 * Zusätzlicher Seitentyp: Page Container. doktype 21.
 *
 * @author Marius Stuebs <marius@gosign.de>
 * @date 2011-07-21
 * @author Caspar Stuebs <caspar@gosign.de>
 * @date 2012-04-26
 */
foreach (array('pages', 'pages_language_overlay') as $pages) {
	t3lib_div::loadTCA($pages);
	t3lib_SpriteManager::addTcaTypeIcon($pages, '21', t3lib_extMgm::extRelPath('go_backend_layout') . 'res/doktype_icon_pagecontainer.png');
	// an der richtigen Stelle den neuen Seitentyp einfügen.
	$newPageType = array('LLL:EXT:go_backend_layout/locallang.xml:pages.doktypes.pagecontainer', '21', 'EXT:go_backend_layout/res/doktype_icon_pagecontainer.png');
	array_splice($TCA[$pages]['columns']['doktype']['config']['items'], 9, 0, array($newPageType));
	// Einstellungsmöglichkeiten werden vom SysOrdner (doktype 254) übernommen.
	$TCA[$pages]['types'][21]['showitem'] = $TCA[$pages]['types'][254]['showitem'];
}

/*
 * set dam_ttcontent field as default thumbnail for table tt_content
 */
if (t3lib_extMgM::isLoaded('dam_ttcontent')) {
	t3lib_div::loadTCA('tt_content');
	$TCA['tt_content']['ctrl']['thumbnail'] = 'tx_damttcontent_files';
}
?>