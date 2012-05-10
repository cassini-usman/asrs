<?php

	// DO NOT REMOVE OR CHANGE THESE 2 LINES:
define('TYPO3_MOD_PATH', '../typo3conf/ext/go_lll/modLabel/');
#define('TYPO3_MOD_PATH', '../ext/templavoila/mod1/');
$MCONF['name'] = 'user_txgolllMLabel';
$MCONF['script'] = '_DISPATCH';
#$MCONF['script'] = '../../../../typo3conf/ext/templavoila/mod1/index.php';
#$MCONF['script'] = 'index.php';

$BACK_PATH='../../../../typo3/';

$MCONF['access'] = 'user,group';

$MLANG['default']['tabs_images']['tab'] = 'moduleicon.gif';
$MLANG['default']['ll_ref'] = 'LLL:EXT:go_lll/modLabel/locallang_mod.xml';

?>