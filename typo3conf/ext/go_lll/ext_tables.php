<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addToInsertRecords('tx_golll_translation');
t3lib_extMgm::allowTableOnStandardPages('tx_golll_translation');

$TCA['tx_golll_translation'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:go_lll/locallang_db.xml:tx_golll_translation',
		'label_userFunc' => 'EXT:go_lll/lib/class.tx_golll_userfuncs.php:tx_golll_userfuncs->getLabelTitle',
			// 'label'     => 'tx_golll_label',
			// 'label_alt'       => 'tx_golll_value',
			// 'label_alt_force' => 1,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY tx_golll_label, tx_golll_langlabel',
			//'delete' => 'deleted',
		'enablecolumns' => array (
			// 'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_golll_translation.gif',
	),
);

t3lib_div::loadTCA('tt_content');
$tempColumns = array(
	'tx_golll_ctype' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:go_lll/locallang_db.xml:tx_golll_translation.tx_golll_ctype',
			'config' => array (
				'type' => 'select',
				'itemsProcFunc' => 'EXT:go_lll/lib/class.tx_golll_userfuncs.php:tx_golll_userfuncs->getCTypeItems',
				'size' => 1,
				'maxitems' => 1,
				'readOnly' => 1,
			)
		),
	'tx_golll_labelcontainer' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:go_tca/locallang_db.xml:tx_gotca_selectoptions',
		'config' => array (
			'type' => 'inline',
			'foreign_table' => 'tx_golll_translation',
			'foreign_match_fields' => array('parentField' => 'tx_golll_labelcontainer'),
			'foreign_field' => 'parentElement',
			'maxitems' => 5000,
			'appearance' => array(
				'expandSingle' => 1,
				'newRecordLinkAddTitle' => 0,
				'newRecordLinkPosition' => 'none',
				'showPossibleLocalizationRecords' => 0,
				'showRemovedLocalizationRecords' => 0,
				'showAllLocalizationLink' => 0,
				'showSynchronizationLink' => 1,
				'useSortable' => 0,
				'enabledControls' => array(
					'localize' => 0,
				),
			),
			'behaviour' => array(
				'localizeChildrenAtParentLocalization' => 0,
				'localizationMode' => 'select',
				'disableMovingChildrenWithParent' => false,
			),
		)
	)
);
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);


/*
 * piLabel
 *
 * @author Daniel Agro <agro@gosign.de>
 */

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['palettes']['go_lll_piLabel']['showitem'] = 'tx_golll_ctype';
$TCA['tt_content']['types'][$_EXTKEY . '_piLabel']['showitem'] = 'CType;;go_lll_piLabel;button;1-1-1,
													tx_golll_labelcontainer,
													';

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:go_lll/locallang_db.xml:tt_content.CType.piLabel',
	$_EXTKEY . '_piLabel',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'),'CType'
);

// additional Sitetype
t3lib_div::loadTCA("pages");


	// add folder icon
$ICON_TYPES['go_lll'] = array('icon' => t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif');
$TCA['pages']['columns']['module']['config']['items'][] = array('LLL:EXT:'.$_EXTKEY.'/locallang_db.xml:tt_content.pagetype_golllpage', 'go_lll', 'EXT:'.$_EXTKEY.'/ext_icon.gif');

// backend module
if (TYPO3_MODE == 'BE') {
	t3lib_extMgm::addModule('user', 'txgolllMLabel', '', t3lib_extMgm::extPath($_EXTKEY) . 'modLabel/');
}

?>
