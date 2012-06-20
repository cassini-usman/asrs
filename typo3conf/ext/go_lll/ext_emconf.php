<?php

########################################################################
# Extension Manager/Repository config file for ext "go_lll".
#
# Auto generated 20-06-2012 04:22
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Gosign Translation',
	'description' => 'Extension zur Pflege von Labels',
	'category' => 'module',
	'author' => 'Daniel Agro, Marius Stuebs',
	'author_email' => 'agro@gosign.de,marius@gosign.de',
	'shy' => '',
	'dependencies' => 'cms,go_pibase,templavoila,go_language',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.2.1',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'go_pibase' => '',
			'templavoila' => '1.6.0-0.0.0',
			'go_language' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:19:{s:9:"ChangeLog";s:4:"6f95";s:16:"ext_autoload.php";s:4:"de8c";s:12:"ext_icon.gif";s:4:"461a";s:17:"ext_localconf.php";s:4:"28d1";s:14:"ext_tables.php";s:4:"4f07";s:14:"ext_tables.sql";s:4:"cc72";s:24:"ext_typoscript_setup.txt";s:4:"4c59";s:8:"icon.png";s:4:"461a";s:16:"locallang_db.xml";s:4:"efcf";s:7:"tca.php";s:4:"522e";s:28:"lib/class.tx_golll_felib.php";s:4:"0abb";s:32:"lib/class.tx_golll_userfuncs.php";s:4:"03f5";s:17:"modLabel/conf.php";s:4:"4571";s:18:"modLabel/index.php";s:4:"b8a0";s:22:"modLabel/locallang.xml";s:4:"bec9";s:26:"modLabel/locallang_mod.xml";s:4:"ffd6";s:23:"modLabel/moduleicon.gif";s:4:"dff5";s:34:"piLabel/class.tx_golll_piLabel.php";s:4:"03bc";s:16:"piLabel/icon.png";s:4:"461a";}',
	'suggests' => array(
	),
);

?>