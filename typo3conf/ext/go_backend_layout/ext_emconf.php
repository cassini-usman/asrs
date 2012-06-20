<?php

########################################################################
# Extension Manager/Repository config file for ext "go_backend_layout".
#
# Auto generated 20-06-2012 04:09
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Go Backend Layout',
	'description' => 'Styling Backend Contentelements + Flexforms automatic and some more features',
	'category' => 'be',
	'author' => 'Caspar Stuebs',
	'author_email' => 'caspar@gosign.de',
	'shy' => '',
	'dependencies' => 'templavoila,t3skin',
	'conflicts' => 't3skin_improved,me_templavoilalayout,me_templavoilalayout2,modern_skin,tm_tvpagemodule',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'Gosign media. GmbH',
	'version' => '2.0.3',
	'constraints' => array(
		'depends' => array(
			'templavoila' => 'templavoila',
			't3skin' => 't3skin',
		),
		'conflicts' => array(
			't3skin_improved' => 't3skin_improved',
			'me_templavoilalayout' => 'me_templavoilalayout',
			'me_templavoilalayout2' => 'me_templavoilalayout2',
			'modern_skin' => 'modern_skin',
			'tm_tvpagemodule' => 'tm_tvpagemodule',
		),
		'suggests' => array(
			'dam' => 'dam',
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:23:{s:16:"ext_autoload.php";s:4:"0f74";s:12:"ext_icon.gif";s:4:"461a";s:17:"ext_localconf.php";s:4:"31dd";s:14:"ext_tables.php";s:4:"304c";s:14:"ext_tables.sql";s:4:"6bf2";s:13:"locallang.xml";s:4:"0585";s:58:"hooks/class.tx_gobackendlayout_addHeaderFilesToBackend.php";s:4:"a1e6";s:47:"hooks/class.tx_gobackendlayout_ajaxRequests.php";s:4:"a752";s:52:"hooks/class.tx_gobackendlayout_getProcessedValue.php";s:4:"01f7";s:56:"hooks/class.tx_gobackendlayout_manipulateWizardItems.php";s:4:"f6b6";s:49:"hooks/class.tx_gobackendlayout_previewContent.php";s:4:"7cda";s:36:"lib/class.tx_gobackendlayout_lib.php";s:4:"e31a";s:39:"lib/class.tx_gobackendlayout_static.php";s:4:"31ba";s:20:"res/template_be.html";s:4:"e057";s:29:"res/css/go_backend_layout.css";s:4:"d41d";s:41:"res/images/doktype_icon_pagecontainer.png";s:4:"1028";s:29:"res/javascript/fieldRights.js";s:4:"0629";s:24:"res/javascript/jquery.js";s:4:"b8d6";s:34:"res/javascript/jqueryNoConflict.js";s:4:"3179";s:31:"res/javascript/newPageWizard.js";s:4:"6912";s:33:"xclass/class.ux_db_list_extra.php";s:4:"2df8";s:47:"xclass/class.ux_tx_templavoila_mod1_wizards.php";s:4:"e565";s:21:"xclass/ux_alt_doc.php";s:4:"c646";}',
);

?>