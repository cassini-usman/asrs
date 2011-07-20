<?php
/*
 * This file is included in localconf.php and sets default project options
 * common to all instances of the project (that is, on every server).
 * Those options can be overwritten in the actual localconf.php file. This is necessary
 * so we do not have to add the localconf.php to our VCS, which could break
 * the site in case the localconf.php file has been modified locally (e.g. via
 * the install tool) and in the repository (merge conflict).
 *
 * WARNING: Changing options here MIGHT not change the actual behavior of the
 * Typo3 installation, because the options MAY have been overwritten in
 * localconf.php.
 */

$typo_db_extTableDef_script = 'extTables.php';

$TYPO3_CONF_VARS['SYS']['sitename'] = 'Web';
$TYPO3_CONF_VARS['SYS']['encryptionKey'] = 'a48baeb82fcab9a7c1fdeb1dec769985845acf78e29ff36aa3a636ec2f5cfdf08735ae47ca7ed21a6dde3eca85730783';
$TYPO3_CONF_VARS['SYS']['compat_version'] = '4.5';
$TYPO3_CONF_VARS['SYS']['setDBinit'] = 'set names utf8';
$TYPO3_CONF_VARS['SYS']['ddmmyy'] = 'd-m-Y';
$TYPO3_CONF_VARS['SYS']['forceReturnPath'] = 1;
$TYPO3_CONF_VARS['SYS']['sqlDebug'] = '1';
$TYPO3_CONF_VARS['SYS']['enableDeprecationLog'] = '0';

$TYPO3_CONF_VARS['BE']['sessionTimeout'] = 36000;
$TYPO3_CONF_VARS['BE']['forceCharset'] = 'utf-8';
$TYPO3_CONF_VARS['BE']['maxFileSize'] = 51200;
$TYPO3_CONF_VARS['BE']['explicitADmode'] = 'explicitAllow';
$TYPO3_CONF_VARS['BE']['fileCreateMask'] = '0664';
$TYPO3_CONF_VARS['BE']['folderCreateMask'] = '0775';
$TYPO3_CONF_VARS['BE']['disable_exec_function'] = '0';
$TYPO3_CONF_VARS['BE']['installToolPassword'] = '66fddfa238df80c759d5d3924ee860af';
$TYPO3_CONF_VARS['BE']['versionNumberInFilename'] = '0';

$TYPO3_CONF_VARS['FE']['disableNoCacheParameter'] = '0';
$TYPO3_CONF_VARS['FE']['compressionLevel'] = '9';
$TYPO3_CONF_VARS['FE']['pageNotFound_handling'] = 'USER_FUNCTION:EXT:go_pibase/class.tx_go404handling.php:tx_go404handling->handle404';
$TYPO3_CONF_VARS['FE']['defaultTypoScript_constants'] = '[GLOBAL]\' . LF . \'extension.pdf_generator.typeNum = 123';
$TYPO3_CONF_VARS['FE']['lifetime'] = '86400';
$TYPO3_CONF_VARS['FE']['dontSetCookie'] = '0';

$TYPO3_CONF_VARS['GFX']['im'] = '1';
$TYPO3_CONF_VARS['GFX']['im_path'] = '/usr/local/bin/';
$TYPO3_CONF_VARS['GFX']['im_version_5'] = 'gm';
$TYPO3_CONF_VARS['GFX']['TTFdpi'] = '96';
$TYPO3_CONF_VARS['GFX']['gdlib_png'] = '0';
$TYPO3_CONF_VARS['GFX']['gdlib_2'] = '0';
$TYPO3_CONF_VARS['GFX']['thumbnails_png'] = '1';
$TYPO3_CONF_VARS['GFX']['png_truecolor'] = '1';

$TYPO3_CONF_VARS['INSTALL']['wizardDone']['tx_coreupdates_installsysexts'] = '1';
$TYPO3_CONF_VARS['INSTALL']['wizardDone']['tx_coreupdates_installnewsysexts'] = '1';


/*
 * Extensions installed by default, and their respective configuration.
 */
$TYPO3_CONF_VARS['EXT']['extList'] = 'extbase,css_styled_content,tsconfig_help,context_help,extra_page_cm_options,impexp,sys_note,tstemplate,tstemplate_ceditor,tstemplate_info,tstemplate_objbrowser,tstemplate_analyzer,func_wizards,wizard_crpages,wizard_sortpages,lowlevel,install,belog,beuser,aboutmodules,setup,taskcenter,info_pagetsconfig,viewpage,t3skin,t3editor,reports,static_info_tables,dam,rsaauth,saltedpasswords,go_pibase,go_tsconfig,static_info_tables_de,recycler,sys_action,version,dam_catedit,dam_index,dam_ttcontent,lfeditor,kickstarter,kb_tv_cont_slide,go_imageedit_be,info,perm,func,cshmanual,feedit,opendocs,scheduler,fluid,workspaces,realurl,go_stopcslide,go_language,tinymce_rte,go_kontakt,naw_securedl,dd_googlesitemap,queo_speedup,templavoila,go_backend_layout';
$TYPO3_CONF_VARS['EXT']['extList_FE'] = 'extbase,css_styled_content,install,t3skin,static_info_tables,dam,rsaauth,saltedpasswords,go_pibase,go_tsconfig,static_info_tables_de,version,dam_catedit,dam_index,dam_ttcontent,lfeditor,kickstarter,kb_tv_cont_slide,go_imageedit_be,feedit,fluid,workspaces,realurl,go_stopcslide,go_language,tinymce_rte,go_kontakt,naw_securedl,dd_googlesitemap,queo_speedup,templavoila,go_backend_layout';
$TYPO3_CONF_VARS['EXT']['allowGlobalInstall'] = '1';

$TYPO3_CONF_VARS['EXT']['extConf']['static_info_tables'] = 'a:2:{s:7:"charset";s:5:"utf-8";s:12:"usePatch1822";s:1:"0";}';
$TYPO3_CONF_VARS['EXT']['extConf']['templavoila'] = 'a:1:{s:7:"enable.";a:1:{s:13:"oldPageModule";s:1:"0";}}';
$TYPO3_CONF_VARS['EXT']['extConf']['dam'] = 'a:6:{s:8:"tsconfig";s:7:"default";s:8:"web_file";s:1:"0";s:15:"hideMediaFolder";s:1:"0";s:8:"mediatag";s:1:"1";s:15:"htmlAreaBrowser";s:1:"1";s:5:"devel";s:1:"0";}';
$TYPO3_CONF_VARS['EXT']['extConf']['lfeditor'] = 'a:14:{s:13:"viewLanguages";s:0:"";s:12:"viewLocalExt";s:1:"1";s:13:"viewGlobalExt";s:1:"0";s:10:"viewSysExt";s:1:"0";s:12:"viewStateExt";s:1:"1";s:9:"extIgnore";s:10:"/^csh_.*$/";s:9:"anzBackup";s:1:"5";s:15:"numTextAreaRows";s:1:"5";s:13:"numSiteConsts";s:1:"6";s:8:"treeHide";s:1:"1";s:10:"execBackup";s:1:"1";s:10:"pathBackup";s:26:"typo3conf/LFEditor/Backup/";s:12:"pathXLLFiles";s:23:"typo3conf/LFEditor/XLL/";s:8:"metaFile";s:34:"typo3conf/LFEditor/Backup/Meta.xml";}';	// Modified or inserted by TYPO3 Extension Manager.
$TYPO3_CONF_VARS['EXT']['extConf']['tinymce_rte'] = 'a:2:{s:10:"loadConfig";s:34:"EXT:tinymce_rte/static/standard.ts";s:18:"pageLoadConfigFile";s:34:"EXT:tinymce_rte/static/pageLoad.ts";}';
$TYPO3_CONF_VARS['EXT']['extConf']['saltedpasswords'] = 'a:2:{s:3:"FE.";a:5:{s:7:"enabled";s:1:"1";s:21:"saltedPWHashingMethod";s:28:"tx_saltedpasswords_salts_md5";s:11:"forceSalted";s:1:"0";s:15:"onlyAuthService";s:1:"1";s:12:"updatePasswd";s:1:"1";}s:3:"BE.";a:5:{s:7:"enabled";s:1:"1";s:21:"saltedPWHashingMethod";s:28:"tx_saltedpasswords_salts_md5";s:11:"forceSalted";s:1:"0";s:15:"onlyAuthService";s:1:"1";s:12:"updatePasswd";s:1:"1";}}';
$TYPO3_CONF_VARS['EXT']['extConf']['realurl'] = 'a:5:{s:10:"configFile";s:26:"typo3conf/realurl_conf.php";s:14:"enableAutoConf";s:1:"1";s:14:"autoConfFormat";s:1:"0";s:12:"enableDevLog";s:1:"0";s:19:"enableChashUrlDebug";s:1:"0";}';
$TYPO3_CONF_VARS['EXT']['extConf']['static_info_tables_de'] = 'a:1:{s:5:"dummy";s:1:"1";}';
$TYPO3_CONF_VARS['EXT']['extConf']['rtehtmlarea'] = 'a:13:{s:21:"noSpellCheckLanguages";s:23:"ja,km,ko,lo,th,zh,b5,gb";s:15:"AspellDirectory";s:15:"/usr/bin/aspell";s:17:"defaultDictionary";s:2:"en";s:14:"dictionaryList";s:2:"en";s:20:"defaultConfiguration";s:95:"Demo (Show-off configuration. Includes pre-configured styles. Not for production environments.)";s:12:"enableImages";s:1:"0";s:20:"enableInlineElements";s:1:"0";s:19:"allowStyleAttribute";s:1:"1";s:24:"enableAccessibilityIcons";s:1:"0";s:16:"enableDAMBrowser";s:1:"0";s:16:"forceCommandMode";s:1:"0";s:15:"enableDebugMode";s:1:"0";s:23:"enableCompressedScripts";s:1:"1";}';	//  Modified or inserted by TYPO3 Extension Manager.
$TYPO3_CONF_VARS['EXT']['extConf']['dam_ttcontent'] = 'a:9:{s:19:"ctype_image_add_ref";s:1:"1";s:26:"ctype_image_add_orig_field";s:1:"0";s:21:"ctype_textpic_add_ref";s:1:"1";s:28:"ctype_textpic_add_orig_field";s:1:"0";s:19:"add_css_styled_hook";s:1:"0";s:19:"add_page_mod_xclass";s:1:"1";s:17:"add_ws_mod_xclass";s:1:"1";s:28:"ctypes_textpic_image_add_ref";s:1:"1";s:35:"ctypes_textpic_image_add_orig_field";s:1:"0";}';
$TYPO3_CONF_VARS['EXT']['extConf']['version'] = 'a:1:{s:18:"showDraftWorkspace";s:1:"0";}';
$TYPO3_CONF_VARS['EXT']['extConf']['dam_index'] = 'a:2:{s:18:"add_media_indexing";s:1:"0";s:23:"add_media_file_indexing";s:1:"1";}';
$TYPO3_CONF_VARS['EXT']['extConf']['naw_securedl'] = 'a:10:{s:11:"securedDirs";s:29:"fileadmin/user_upload/private";s:8:"filetype";s:40:"pdf|jpe?g|gif|png|doc|xls|rar|tgz|tar|gz";s:13:"forcedownload";s:1:"0";s:17:"forcedownloadtype";s:26:"pdf|doc|xls|rar|tgz|tar|gz";s:6:"domain";s:46:"http://mydomain.com|http://my.other.domain.org";s:12:"cachetimeadd";s:4:"3600";s:5:"debug";s:1:"0";s:3:"log";s:1:"0";s:14:"outputFunction";s:8:"readfile";s:15:"outputChunkSize";s:7:"1048576";}';

