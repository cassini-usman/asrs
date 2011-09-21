<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$LANG = t3lib_div::makeInstance('language');
$LANG->init($BE_USER->uc['lang']);

$tempColumns = array (
	'tx_gokontakt_redirect' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_redirect',
		'config' => array (
			'type'	 => 'input',
			'size'	 => '15',
			'max'	  => '255',
			'checkbox' => '',
			'eval'	 => 'trim',
			'wizards'  => array(
				'_PADDING' => 2,
				'link'	 => array(
					'type'		 => 'popup',
					'title'		=> 'Link',
					'icon'		 => 'link_popup.gif',
					'script'	   => 'browse_links.php?mode=wizard',
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
				)
			)
		)
	),
	/* EMAIL FIELDS */
	'tx_gokontakt_emailFrom' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailFrom',
		'config' => array (
			'type' => 'input',
			'size' => '50',
			'eval' => 'required',
		)
	),
	'tx_gokontakt_emailFromName' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailFromName',
		'config' => array (
			'type' => 'input',
			'size' => '50',
			'eval' => 'required',
		)
	),
	'tx_gokontakt_emailToAdmin' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailToAdmin',
		'config' => array (
			'type' => 'input',
			'size' => '50',
			'eval' => 'required',
		)
	),
	'tx_gokontakt_emailSubject' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailSubject',
		'config' => array (
			'type' => 'input',
			'size' => '50',
			'eval' => 'required',
			'default' => $LANG->sL('LLL:EXT:go_kontakt/locallang_db.xml:default.tx_gokontakt_emailSubject')
		),
	),
	'tx_gokontakt_emailBody' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailBody',
		'config' => array (
			'type' => 'text',
			'cols' => '80',
			'rows' => '10',
			'eval' => 'required',
			'default' => $LANG->sL('LLL:EXT:go_kontakt/locallang_db.xml:default.tx_gokontakt_emailBody')
		),
	),
	'tx_gokontakt_emailAdminSubject' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailAdminSubject',
		'config' => array (
			'type' => 'input',
			'size' => '50',
			'eval' => 'required',
			'default' => $LANG->sL('LLL:EXT:go_kontakt/locallang_db.xml:default.tx_gokontakt_emailAdminSubject')
		)
	),
	'tx_gokontakt_emailAdminBody' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailAdminBody',
		'config' => array (
			'type' => 'text',
			'cols' => '80',
			'rows' => '10',
			'eval' => 'required',
			'default' => $LANG->sL('LLL:EXT:go_kontakt/locallang_db.xml:default.tx_gokontakt_emailAdminBody')
		)
	),
	'tx_gokontakt_emailNewsletterSubject' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailNewsletterSubject',
		'config' => array (
			'type' => 'input',
			'size' => '50',
			'eval' => 'required',
			'default' => $LANG->sL('LLL:EXT:go_kontakt/locallang_db.xml:default.tx_gokontakt_emailNewsletterSubject')
		)
	),
	'tx_gokontakt_emailNewsletterBody' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailNewsletterBody',
		'config' => array (
			'type' => 'text',
			'cols' => '80',
			'rows' => '10',
			'eval' => 'required',
			'default' => $LANG->sL('LLL:EXT:go_kontakt/locallang_db.xml:default.tx_gokontakt_emailNewsletterBody')
		)
	),
	'tx_gokontakt_newsletterUsergroup' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_newsletterUsergroup',
		'config' => array (
			'type' => 'select',
			'items' => array (
				array('',0),
			),
			'foreign_table' => 'fe_groups',
			'foreign_table_where' => 'ORDER BY fe_groups.uid',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
		)
	),
);

t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);

$TCA['tt_content']['types'][$_EXTKEY . '_piKontakt']['showitem'] = '
--div--;LLL:EXT:go_kontakt/locallang_db.xml:div.data, CType;;4;button;1-1-1, header;;3;;2-2-2,pages,
--div--;LLL:EXT:go_kontakt/locallang_db.xml:div.email, tx_gokontakt_emailFrom,tx_gokontakt_emailFromName,tx_gokontakt_emailToAdmin,
--div--;LLL:EXT:go_kontakt/locallang_db.xml:div.emailtext, tx_gokontakt_emailSubject,tx_gokontakt_emailBody,tx_gokontakt_emailAdminSubject,tx_gokontakt_emailAdminBody,
--div--;LLL:EXT:go_kontakt/locallang_db.xml:div.newsletter, tx_gokontakt_newsletterUsergroup;;;;1-1-1,tx_gokontakt_emailNewsletterSubject,tx_gokontakt_emailNewsletterBody,
';

// Contact Form

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.go_kontakt.CType_piKontakt',
	$_EXTKEY . '_piKontakt',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'piKontakt/icon.gif'
),'CType');

$TCA['pages']['columns']['module']['config']['items']['newsletter'] = array('LLL:EXT:go_kontakt/locallang_db.xml:pages.containsPlugin', 'newsletter', 'EXT:go_kontakt/ext_icon.gif');

?>