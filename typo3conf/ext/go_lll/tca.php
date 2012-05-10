<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
// var_dump($TCA['tx_golll_translation']['ctrl']);die();
$TCA['tx_golll_translation'] = array (
	'ctrl' => $TCA['tx_golll_translation']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,tx_golll_ctype,tx_golll_label,tx_golll_value'
	),
	'feInterface' => $TCA['tx_golll_translation']['feInterface'],
	'columns' => array (
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'parentElement' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:go_tca/locallang_db.xml:tx_gotca_selectoptions.parentElement',
			'l10n_mode' => 'exclude',
			'config' => array (
				'type' => 'select',
				'foreign_table' => 'tt_content',
				'foreign_table_where' => 'AND tt_content.CType=\'go_lll_piLabel\' AND tt_content.tx_golll_ctype=###REC_FIELD_tx_golll_ctype### ORDER BY tt_content.sorting',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'wizards' => array(
					'_PADDING'  => 2,
					'_VERTICAL' => 1,
					'edit' => array(
						'type'                     => 'popup',
						'title'                    => 'Edit',
						'script'                   => 'wizard_edit.php',
						'popup_onlyOpenIfSelected' => 1,
						'icon'                     => 'edit2.gif',
						'JSopenParams'             => 'height=800,width=610,status=0,menubar=0,scrollbars=1',
					),
				),
			)
		),
		'tx_golll_langlabel' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:go_lll/locallang_db.xml:tx_golll_translation.tx_golll_langlabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'readOnly' => '1',
			)
		),
		'tx_golll_ctype' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:go_lll/locallang_db.xml:tx_golll_translation.tx_golll_ctype',
			'config' => array (
				'type' => 'select',
				'itemsProcFunc' => 'EXT:go_lll/lib/class.tx_golll_userfuncs.php:tx_golll_userfuncs->getCTypeItems',
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'tx_golll_label' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:go_lll/locallang_db.xml:tx_golll_translation.tx_golll_label',
			'config' => Array (
				'type' => 'input',
				'size' => '30',
			)
		),
		'tx_golll_value' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:go_lll/locallang_db.xml:tx_golll_translation.tx_golll_value',
			'config' => array (
				'type' => 'text',
				'cols' => '48',
				'rows' => '4',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'tx_golll_value')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>