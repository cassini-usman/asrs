<?php

########################################################################
# Extension Manager/Repository config file for ext "go_backend_layout".
#
# Auto generated 23-08-2012 17:23
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
	'dependencies' => 'templavoila,t3skin,golib_mustache',
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
	'version' => '2.1.4',
	'constraints' => array(
		'depends' => array(
			'templavoila' => 'templavoila',
			't3skin' => 't3skin',
			'golib_mustache' => 'golib_mustache',
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
	'_md5_values_when_last_written' => 'a:34:{s:20:"class.ext_update.php";s:4:"5a82";s:16:"ext_autoload.php";s:4:"5fb9";s:21:"ext_conf_template.txt";s:4:"eb0b";s:12:"ext_icon.gif";s:4:"461a";s:17:"ext_localconf.php";s:4:"0852";s:14:"ext_tables.php";s:4:"304c";s:14:"ext_tables.sql";s:4:"5d67";s:13:"locallang.xml";s:4:"4228";s:58:"hooks/class.tx_gobackendlayout_addHeaderFilesToBackend.php";s:4:"a1e6";s:47:"hooks/class.tx_gobackendlayout_ajaxRequests.php";s:4:"a752";s:52:"hooks/class.tx_gobackendlayout_getProcessedValue.php";s:4:"01f7";s:56:"hooks/class.tx_gobackendlayout_manipulateWizardItems.php";s:4:"1aeb";s:55:"hooks/class.tx_gobackendlayout_pageModulePreContent.php";s:4:"5afc";s:49:"hooks/class.tx_gobackendlayout_previewContent.php";s:4:"3669";s:48:"hooks/class.tx_gobackendlayout_userAuthGroup.php";s:4:"e9c8";s:36:"lib/class.tx_gobackendlayout_lib.php";s:4:"39e9";s:39:"lib/class.tx_gobackendlayout_static.php";s:4:"abdf";s:29:"res/css/go_backend_layout.css";s:4:"a5c2";s:23:"res/css/stickyNotes.css";s:4:"36f3";s:41:"res/images/doktype_icon_pagecontainer.png";s:4:"1028";s:25:"res/images/notesClose.png";s:4:"e3b2";s:24:"res/images/notesIcon.png";s:4:"39eb";s:26:"res/images/notesResize.png";s:4:"1b24";s:29:"res/javascript/fieldRights.js";s:4:"0629";s:24:"res/javascript/jquery.js";s:4:"b8d6";s:36:"res/javascript/jQuery.stickynotes.js";s:4:"aeeb";s:34:"res/javascript/jqueryNoConflict.js";s:4:"3179";s:26:"res/javascript/jQueryUi.js";s:4:"2701";s:31:"res/javascript/newPageWizard.js";s:4:"6912";s:23:"res/javascript/notes.js";s:4:"ac27";s:39:"res/templates/template_be.mustache.html";s:4:"5280";s:33:"xclass/class.ux_db_list_extra.php";s:4:"2df8";s:47:"xclass/class.ux_tx_templavoila_mod1_wizards.php";s:4:"f432";s:21:"xclass/ux_alt_doc.php";s:4:"c646";}',
);

?>