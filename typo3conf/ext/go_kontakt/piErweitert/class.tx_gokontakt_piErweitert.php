<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Elio Wahlen <vorname at gosign dot de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once( t3lib_extMgm::extPath('go_pibase') . 'class.tx_gopibase.php' );


/**
 * Plugin 'Contact Form' for the 'go_kontakt' extension.
 *
 * @author	Elio Wahlen <vorname at gosign dot de>
 * @package	TYPO3
 * @subpackage	tx_gokontakt
 */
class tx_gokontakt_piErweitert extends tx_gopibase {
	var $prefixId      = 'tx_gokontakt_piErweitert';		// Same as class name
	var $scriptRelPath = 'piErweitert/class.tx_gokontakt_piErweitert.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'go_kontakt';	// The extension key.
	var $pi_checkCHash = true;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->loadTemplate();

		//get DATA
		$this->data = $this->cObj->data;
		$this->data['uid'] = ($this->data['_LOCALIZED_UID'] > 0)?$this->data['_LOCALIZED_UID']:$this->data['uid'];
		$this->submitted = intval(t3lib_div::_GP('submitted' ));

		// Newsletter Confirmation Link Clicked ::::::::=> EXIT HERE
		if ( isset($_GET['nlCfm']) ) {
			// automatically substitute language markers
			$this->substituteLanguageMarkers();
			return $this->checkNewsletter();
		}

		if ( !$this->submitted ) { // first call
			// reset session
			$GLOBALS["TSFE"]->fe_user->setKey("ses",$this->extKey, NULL);
			// reset the flag for successful transmission
			$GLOBALS["TSFE"]->fe_user->setKey("ses","order_successfully_submitted", 0);
		}

		// writes POST to SESSION and vice versa
		$this->updatePostAndSession();
		// automatically substitute value markers
		$this->substituteValueMarkers();
		// automatically substitute language markers
		$this->substituteLanguageMarkers();

		// Step handling
		$this->step = intval(t3lib_div::_GP('step'));
		$this->step = ($this->step==0) ? 1 : $this->step; // if first visit
		// the next step is coded in an array (e.g. this->nextStep[2]=Weiter)
		$this->nextStep = intval(end(array_keys(t3lib_div::_GP('nextStep'))));

		// fehler nur wenn wir auf die folgeseite wollen
		if ( $this->nextStep > $this->step ) {
			$this->doErrorChecks();
		}
		if ( !$this->errors['any'] && $this->nextStep ) { // if no error, go to required step
			$this->step = $this->nextStep;
		}

		if ( $this->step == 1 ) {
			$content = $this->renderStep1();
		} elseif( $this->step == 2 ) {
			$this->sendEmails();
			$this->addNewsletter();
			$content = $this->renderConfirmation();
		}

		return $content;
	}

	function renderStep1() {
		$this->addMarker( 'HEADER', $this->data['header']);
		// substitute the error markers
		$this->substituteErrorMarkers('KONTAKT_STEP_1');
		$this->substituteCheckedMarkers('KONTAKT_STEP_1');
		return $this->parseTemplate('KONTAKT_STEP_1');
	}

	function renderConfirmation() {
		return $this->parseTemplate('CONFIRMATION');
	}

	function sendEmails() {
		if ( $GLOBALS["TSFE"]->fe_user->getKey("ses","order_successfully_submitted") ) { // if already successfully submitted...
			return false;
		}



		$markerArrayEmailAdmin['###CHECKLIST###'] = $this->pi_getLL('newsletter.'.$this->values['newsletter']);

		$emailUser = str_replace("<br />", "\n", $this->parseTemplate('EMAIL_USER'));
		$emailAdmin = str_replace("<br />", "\n", $this->parseTemplate('EMAIL_ADMIN', $markerArrayEmailAdmin));
		$emailFrom = $this->data['tx_gokontakt_emailFrom'];
		$emailFromName = $this->data['tx_gokontakt_emailFromName'];
		$emailToAdmin = $this->data['tx_gokontakt_emailToAdmin'];
		$this->sendEmail($this->pi_getLL('subject_email_user'), $emailUser, '', $emailFrom, $emailFromName, $this->values['email']);
		$this->sendEmail($this->pi_getLL('subject_email_admin'), $emailAdmin, '', $emailFrom, $emailFromName, $emailToAdmin);
		$GLOBALS["TSFE"]->fe_user->setKey("ses","order_successfully_submitted", 1);
	}

	function emailNewsletterConfirmation($emailTo, $emailFrom, $emailFromName) {
		$emailFrom = $this->data['tx_gokontakt_emailFrom'];
		$emailFromName = $this->data['tx_gokontakt_emailFromName'];
		$this->addMarker('LINK_NEWSLETTER', $GLOBALS['TSFE']->baseUrl.'?id='.$GLOBALS['TSFE']->id.'&nlCfm='.$this->newsletterHash.'&u='.$this->userID);
		$emailUser = $this->parseTemplate('EMAIL_NEWSLETTER');
		$this->sendEmail($this->pi_getLL('subject_newsletter_confirmation'), $emailUser, '', $emailFrom, $emailFromName, $emailTo);
	}

	function addNewsletter() {


		if ( $GLOBALS["TSFE"]->fe_user->getKey("ses","order_successfully_submitted") ) { // if already successfully submitted...
			return false;
		}
		if ( empty($this->values['newsletter']) ) { // only if newsletter was checked
			return;
		}

		$GLOBALS['TYPO3_DB']->exec_INSERTquery( 'fe_users', array(
			'pid' => $this->conf['newsletterPID'],
			'deleted' => '0',
			'disable' => '1',
			'tstamp' => time(),
			'crdate' => time(),
			'username' => $this->values['email'],
			'usergroup' => 2,
			'title' => $this->values['title'],
			'first_name' => $this->values['firstname'],
			'last_name' => $this->values['lastname'],
			'address' => $this->values['street'] . ' ' . $this->values['street_nr'],
			'zip' => $this->values['plz'],
			'city' => $this->values['city'],
			'company' => $this->values['company'],
			'telephone' => $this->values['telephone'],
			'email' => $this->values['email'] ) );

		// get the id
		$this->userID = mysql_insert_id();

		// get the whole row
		$row = current($GLOBALS['TYPO3_DB']->exec_SELECTgetRows( '*', 'fe_users', 'uid=' . $this->userID ));
		$this->newsletterHash = substr(sha1(serialize($row)),1,6); // first 6 letters from hash
		// send the confirmation email
		$this->emailNewsletterConfirmation($this->values['email'], $emailFrom, $emailFromName );
	}

	function checkNewsletter() {
		$hash = t3lib_div::_GP('nlCfm');
		$userID = intval(t3lib_div::_GP('u'));

		// get the whole row
		$row = current($GLOBALS['TYPO3_DB']->exec_SELECTgetRows( '*', 'fe_users', 'uid=' . $userID ));
		$realHash = substr(sha1(serialize($row)),1,6); // first 6 letters from hash
		if ( $row['disable'] && ($realHash == $hash) ) { // hash matches
			// enable the user
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery( 'fe_users', 'uid=' . $userID, array(
				'uid' => $userID,
				'disable' => '0' ) );
			return $this->parseTemplate('NEWSLETTER_OK');
		} else {
			return $this->parseTemplate('NEWSLETTER_ERROR');
		}
	}

	/**
	 * This substitutes checked-markers
	 *
	 * ###checked_xxx_value### will be substituted by checked="checked" when xxx=value
	 *
	 */
	function substituteCheckedMarkers($subpart) {
		$code = $this->parseTemplate($subpart);
		$markers = array();
		while ( ($code = stristr( $code, '###checked_')) !== FALSE ) {
			$marker = substr( $code, 11, strpos(substr($code,11),'###'));
			$value = substr( $marker, strpos( $marker, '_' ) + 1 );
			$marker = substr( $marker, 0, strpos( $marker, '_' ) );
			$markers[] = $marker;
			// strip the ###checked_ thing, so that we can find the next one
			$code = substr($code,11);
			// if value matches, fill in the checked="checked"
			$this->markerArray['###checked_'.$marker.'_'.$value.'###'] = (strcmp( $this->values[$marker], $value ) == 0) ? ' checked="checked" ' : ' ';
		}
	}

	function substituteOptionMarker($marker) {
		$key = substr($marker,8);
		$options = '';
		$i = 0;
		$locallang = array_merge( $this->LOCAL_LANG['default'], $this->LOCAL_LANG[$this->LLkey]);
		foreach( $locallang as $langKey => $value ) {
			// find all the language option markers (key.00, key.01, key.02, key.03 ...)
			// set them to selected, if value matches
			if ( substr($langKey,0,strlen($key)+1) == $key.'.' ) {
				$selected = ($this->values[$key] == $value) ? 'selected="selected"' : '';
				$text = $value;
				$value = $i ? $value : '';

				/**
				 * 2011 - Michael Spahn <michael@gosign.de>
				 */
				if(preg_match('/|/', $value?$value:$text)) {
					$value_ar = explode('|', $value?$value:$text);
					$value_id = $value_ar[1];
					$text = $value_ar[0];
				} else {
					$value_id = $value;
				}

				$options .= '<option value="'.$value.'" '.$selected.'>'.$text.'</option>'.chr(13);
				$i++;
			}
		}
		$this->markerArray['###'.$marker.'###'] = $options;
	}


	/**
	 * This substitutes the error-markers
	 *
	 * ###error_xxx_marker### will be substituted
	 *
	 */
	function substituteErrorMarkers($subpart) {
		$code = $this->parseTemplate($subpart);
		$markers = array();
		while ( ($code = stristr( $code, '###error:')) !== FALSE ) {
			$marker = substr( $code, 9, strpos(substr($code,9),'###'));
			$what = substr( $marker, strpos( $marker, ':' ) + 1 );
			$marker = substr( $marker, 0, strpos( $marker, ':' ) );
			$markers[] = $marker;
			// strip the ###error: thing, so that we can find the next one
			$code = substr($code,9);

			// for the class type, we want to know if there is an error in general
			$errorToCheck = ($what == 'class') ? 'any' : $what;

			// several conditions can be combined by a '+' sign
			$isError = false;
			$fields = explode( '+', $marker );
			foreach( $fields as $field ) {
				$isError = $isError || $this->errors[$field][$errorToCheck];
			}
			$replacement = '';
			if ( $isError ) {
				// try to find in locallang: error_field_condition
				$replacement = $this->pi_getLL('error_'.$field.'_'.$what);
				// try to find in locallang: error_condition (as a second choice)
				$replacement = empty($replacement) ? $this->pi_getLL('error_'.$what) : $replacement;
			}

			$this->markerArray['###error:'.$marker.':'.$what.'###'] = $replacement;
		}
	}

	function doErrorChecks() {
		if ( !$this->submitted || ($this->nextStep < $this->step) ) { // no error check if not submitted
			return;
		}
		foreach( $this->conf['errorCheck.'][$this->step.'.'] as $type => $fields ) {
			$type = substr( $type, 0, -1); // strip the dot
			foreach( $fields as $field => $value ) {
				switch( $type ) {
					case 'obligatory':
						if ( is_array($this->values[$field]) ) {
							$this->errors[$field][$type] = (count( $this->values[$field] ) == 0);
						} else {
							$this->errors[$field][$type] = (strlen(trim( $this->values[$field] )) == 0) ? 1 : 0;
						}
						break;
					case 'nonezero':
						if ( is_array($this->values[$field]) ) {
							$this->errors[$field][$type] = (count( $this->values[$field] ) == 0);
						} else {
							$this->errors[$field][$type] = (floatval(trim( $this->values[$field]))==0) ? 1 : 0;
						}
						break;
					case 'email':
						$this->errors[$field][$type] = (t3lib_div::validEmail( trim( $this->values[$field] ) ) ? 0 : 1);
						break;
					case 'plz':
						$this->errors[$field][$type] = preg_match ("/^[0-9]{5}/", trim( $this->values[$field] )) ? 0 : 1;
						break;
				}
			}
		}
		// insert the 'any'-indicator, that tells us if the field has any error at all
		$this->errors['any'] = 0;
		foreach( $this->errors as $field => $checks ) {
			$this->errors[$field]['any'] = 0;
			foreach( $checks as $type => $value ) {
				if ( $value ) {
					$this->errors[$field]['any'] = 1;
					$this->errors['any'] = 1;
				}
			}
		}
	}


	/*
	* Store all POST Data in session
	*/
	function updatePostAndSession() {
		// get session data
		$GLOBALS["TSFE"]->fe_user->fetchSessionData();
		$this->values = array();
		// write session to array
		foreach( $GLOBALS["TSFE"]->fe_user->sesData[$this->extKey] as $key=>$value) {
			$this->values[$key] = $value;
		}
		// write POST to array (POST overrides session)
		foreach( $_POST as $key=>$value) {
			if (is_array($value)) { // aus t3lib_div::_GP() geklaut
				t3lib_div::stripSlashesOnArray($value);
			} else {
				$value = stripslashes($value);
			}
			$this->values[$key] = $value;
		}

		// write updated Array to session (and fetch it to $GLOBALS["TSFE"]->fe_user->sesData)
		$GLOBALS["TSFE"]->fe_user->setKey("ses",$this->extKey, $this->values);
		$GLOBALS["TSFE"]->fe_user->fetchSessionData();
		// update POST
		$_POST = $this->values;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_kontakt/piErweitert/class.tx_gokontakt_piErweitert.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_kontakt/piErweitert/class.tx_gokontakt_piErweitert.php']);
}

?>
