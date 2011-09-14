<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumns = array (
    'tx_gokontakt_redirect' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_redirect',        
        'config' => array (
            'type'     => 'input',
            'size'     => '15',
            'max'      => '255',
            'checkbox' => '',
            'eval'     => 'trim',
            'wizards'  => array(
                '_PADDING' => 2,
                'link'     => array(
                    'type'         => 'popup',
                    'title'        => 'Link',
                    'icon'         => 'link_popup.gif',
                    'script'       => 'browse_links.php?mode=wizard',
                    'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                )
            )
        )
    ),
	'tx_gokontakt_emailFrom' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailFrom',        
        'config' => array (
            'type' => 'input',    
            'size' => '30',    
            'eval' => 'required',
        )
    ),
	'tx_gokontakt_emailFromName' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailFromName',        
        'config' => array (
            'type' => 'input',    
            'size' => '30',    
        )
    ),
	'tx_gokontakt_emailToAdmin' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.tx_gokontakt_emailToAdmin',        
        'config' => array (
            'type' => 'input',    
            'size' => '30',    
            'eval' => 'required',
        )
    ),
);

t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);

$TCA['tt_content']['types'][$_EXTKEY . '_piKontakt']['showitem'] = 'CType;;4;button;1-1-1, header;;3;;2-2-2,pages,tx_gokontakt_emailFrom,tx_gokontakt_emailFromName,tx_gokontakt_emailToAdmin';

// Contact Form

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:go_kontakt/locallang_db.xml:tt_content.go_kontakt.CType_piKontakt',
	$_EXTKEY . '_piKontakt',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'piKontakt/icon.gif'
),'CType');

?>