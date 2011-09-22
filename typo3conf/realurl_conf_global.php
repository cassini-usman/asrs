<?php
$TYPO3_CONF_VARS['EXTCONF']['realurl'] = array(
	'_DEFAULT' => array(
		'init' => array(
			'enableCHashCache' => 1,
			'appendMissingSlash' => 'ifNotFile,redirect',
			'adminJumpToBackend' => 1,
			'enableUrlDecodeCache' => 1,
			'enableUrlEncodeCache' => 1,
			'emptyUrlReturnValue' => '/',
			'disableErrorLog' => 1,
		),
		'pagePath' => array(
			'rootpage_id' => 3,
			'type' => 'user',
			'userFunc' => 'EXT:realurl/class.tx_realurl_advanced.php:&tx_realurl_advanced->main',
			'spaceCharacter' => '-',
			'languageGetVar' => 'L',
		),
		'fileName' => array(
			'defaultToHTMLsuffixOnPrev' => 1,
			'acceptHTMLsuffix' => 1,
		),
		'preVars' => array (
			0 => array (
				'GETvar' => 'L',
				'userFunc' => 'tx_golanguage->getRealurlLanguagePrevar',
			),
		),
	),
);

/*
 * For configuration of a multi domain server:
 * - uncomment the following lines
 * - update the rootpage_id
 *   - maybe you like to update some other config...
 * - replace REAL_DOMAIN{X} with the real (live) domains
 *
 * For development, please use a local domain config in local/realurl_conf.php
 */
/*
 * prepare domain config
 */
// $TYPO3_CONF_VARS['EXTCONF']['realurl']['DOMAIN1'] = $TYPO3_CONF_VARS['EXTCONF']['realurl']['_DEFAULT'];

// $TYPO3_CONF_VARS['EXTCONF']['realurl']['DOMAIN2'] = $TYPO3_CONF_VARS['EXTCONF']['realurl']['_DEFAULT'];
// $TYPO3_CONF_VARS['EXTCONF']['realurl']['DOMAIN2']['pagePath']['rootpage_id'] = 374;

/*
 * copy domain config to real domains
 */
// $TYPO3_CONF_VARS['EXTCONF']['realurl']['REAL_DOMAIN1'] = $TYPO3_CONF_VARS['EXTCONF']['realurl']['DOMAIN1'];

// $TYPO3_CONF_VARS['EXTCONF']['realurl']['REAL_DOMAIN2'] = $TYPO3_CONF_VARS['EXTCONF']['realurl']['DOMAIN2'];

?>