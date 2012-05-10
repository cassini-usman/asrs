<?php

require_once(PATH_t3lib . 'utility/class.t3lib_utility_http.php');
//require_once('../../../../utility/class.t3lib_utility_http.php');

/**
 * @author Marius Stuebs <marius@gosign.de>
 *
 * @date: 2012-03-27
 *
 * This function is just a redirect to templavoia WITH the page uid
 * (i found no other way to set the UID of the page).
 *
 */
class tx_golll_modLabel {
	/**
	 * init
	 *
	 * Redirect to templavoila.
	 */
	function init() {
		$directory = substr($_SERVER["SCRIPT_NAME"], 0, strrpos($_SERVER["SCRIPT_NAME"], '/'));
		$directory .= '/../typo3conf/ext/templavoila/mod1/';
		$redirectLocation = $directory . 'index.php?id=' . $this->getTranslationPageUID();
		t3lib_utility_http::redirect($redirectLocation );
		exit();
	}

	/**
	 * @author Marius Stuebs <marius@gosign.de>
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @date: 2012-02-16
	 *
	 * This function returns the UID of the translation page
	 *
	*/
	function getTranslationPageUID () {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'pages', 'module="go_lll" and deleted="0"', '', '', '1');
		if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			return $row['uid'];
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/modLabel/index.php'])    {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/modLabel/index.php']);
}

	// Make instance:
$SOBE = t3lib_div::makeInstance('tx_golll_modLabel');
$SOBE->init();


?>