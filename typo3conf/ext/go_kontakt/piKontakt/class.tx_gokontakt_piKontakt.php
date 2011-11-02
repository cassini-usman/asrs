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

require_once('typo3conf/ext/go_kontakt/lib/recaptchalib.php');

/**
 * Plugin 'Contact Form' for the 'go_kontakt' extension.
 *
 * @author	Elio Wahlen <vorname at gosign dot de>
 * @package	TYPO3
 * @subpackage	tx_gokontakt
 */
class tx_gokontakt_piKontakt extends tx_gopibase {
	var $prefixId      = 'tx_gokontakt_piKontakt';		// Same as class name
	var $scriptRelPath = 'piKontakt/class.tx_gokontakt_piKontakt.php';	// Path to this script relative to the extension dir.
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

		////
		$this->initForm($conf);
		$step = $this->stepLogic();
		$this->processData($step);
		$content = $this->renderForm($step);
		////

		return $this->pi_wrapInBaseClass($content);
	}

	/*
	 * Initializes the Form
	 *  - loads the Configuration
	 *  - clears the cache (on first call)
	 *  - updates the session with GET/POST vars
	 *
	 * @param	$conf	the TS configuration of the plugin
	 *
	 */
	function initForm(&$conf) {
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		// Create shortcuts to these arrays
		$this->conf = &$conf;
		$this->data = &$this->cObj->data;

		$this->newsletterPID = current($GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow( 'uid', 'pages', "module='newsletter'" ));
		$this->newsletterRegEnabled = $this->newsletterPID && $this->data['tx_gokontakt_newsletterUsergroup'];

		$this->submitted = (int) $this->piVars['submitted'];
		if ( !$this->submitted ) { // first call
			// reset session
			$GLOBALS["TSFE"]->fe_user->setKey("ses", $this->extKey, NULL);
			$GLOBALS["TSFE"]->fe_user->setKey("ses", $this->extKey . "_successfully_submitted", 0);
		}

		if ( $this->piVars['uid'] == $this->data['uid'] ) { // if this form has been submitted
			$this->mergePiVarsWidthSession();
		} else { // if a different form has been submitted
			$this->piVars = array();
		}
	}

	/*
	 * Calculates the number of the next step
	 * based on the desired next step
	 * and on conditions (errors)
	 *
	 */
	function stepLogic() {
			// for newsletter confirmation: no step processing
			// newsletter auth does not use piVars to avoid line breaks in long links
		if ( t3lib_div::_GP('nlAuth') ) {
			$this->subpartName = $this->activateNewsletter() ? 'NEWSLETTER_OK' : 'NEWSLETTER_ERROR';
			return 0;
		}

		$this->lastStep = isset($this->conf['lastStep']) ? $this->conf['lastStep'] : 2;

			// Current Step initialization
		$step = intval($this->piVars['step'] );
		$step = ($step==0) ? 1 : $step; // if first visit
			// The step that we're coming from cannot be the last step.
		$step = min( $step, $this->lastStep-1 );

		// Next Step initialization
		// the next step is coded in an array (e.g. nextStep[2]=Weiter)
		$nextStep = intval(end(array_keys($this->piVars['nextStep'])));
		// Error checks (if clicking in forward direction)
		if ( ($nextStep > $step) || ($nextStep == $this->lastStep) ) {
			$this->doErrorChecks($step);
		}

		// If no error, update the current step to the next one
		if ( !$this->errors['any'] && $nextStep ) {
			$step = min($nextStep, $this->lastStep ); // default last step is 2
		}
		$this->subpartName = $step ? 'STEP_' . $step : $this->subpartName;
		return $step;
	}

	/*
	 * Loads the Template and substitutes the markers
	 *
	 * @param	step		the step's number
	 */
	function prepareTemplate( $step ) {
		$this->loadTemplate();
		// automatically substitute value markers
		$this->substituteValueMarkers( array( 'subpartName' => 'STEP_' . $step ) );
		// automatically substitute language markers
		$this->substituteLanguageMarkers(array('subpartName' => 'STEP_' . $step));
		$this->substituteLanguageMarkers(array('subpartName' => 'CAPTCHA_TEMPLATE'));
		$this->substituteFormMarkers(array('subpartName' => 'STEP_' . $step));
		$this->substituteFormMarkers(array('subpartName' => 'CAPTCHA_TEMPLATE'));
	}

	/*
	 * Renders the complete Form
	 * and calls optional subroutines like 'customRenderStep1', 'customRenderStep2', 'customRenderStep3'
	 *
	 * @param	step		the step number
	 *
	 * @return				the form's HTML
	*/
	function renderForm( $step ) {
		$langObj = t3lib_div::makeInstance('tx_golanguage');

		// load template and substitute the markers
		$this->prepareTemplate( $step );

		$this->addMarker('HEADER', $this->cObj->cObjGetSingle($GLOBALS['TSFE']->tmpl->setup['lib.']['stdheader'], $GLOBALS['TSFE']->tmpl->setup['lib.']['stdheader.']));
		$this->addMarker('FORM_LOGIC_FIELDS', $this->createHiddenField('submitted', 1) . $this->createHiddenField('step', $step) . $this->createHiddenField('uid', $this->data['uid']));
		$this->addMarker('PROTOCOL', t3lib_div::getIndpEnv('TYPO3_SSL') ? 'https' : 'http');
		$this->addMarker('LANG', $langObj->getLanguageIso($GLOBALS['TSFE']->sys_language_uid));
			// hide captcha if already verified, or if spam protection has been disabled
		$this->addMarker('SPAMPROTECT', ($this->conf['useSpamProtection'] && !$this->captchaVerified()) ? $this->parseTemplate('CAPTCHA_TEMPLATE') : '');
		if ( !$this->newsletterRegEnabled ) {
			$this->addSMarker('NEWSLETTER', '');
		}

		if ( method_exists($this, $methodName = 'renderStep' . $step) ) {
			call_user_func( array(&$this, $methodName) );
		}
		return $this->parseTemplate($this->subpartName);
	}

	/*
	 * This is for custom step rendering
	 *
	 * renderStep1, renderStep2 etc. will be called if they exist
	 * you can simply add other functions like this
	 */
	function renderStep1() {
		$this->substituteOptionMarker('region');
	}

	/*
	 * Creates the HTML code for an hidden input field
	 * To prevent XSS, all special characters will be encoded to html entities
	 *
	 * @param	name		the hidden field's name
	 * @param	value		the hidden field's value (optional, will be an empty string, if ommitted)
	*/
	function createHiddenField( $name, $value = '' ) {
		$nameAttr = $this->makePiVar(htmlspecialchars($name));
		return '<input type="hidden" name="' . $nameAttr . '" value="' . $value . '" />';
	}


	/*
	 * Does some Processing just before rendering the Form
	 * (can be customized)
	 *
	 */
	function processData($step) {
			// if already successfully submitted, we skip any further processing
		if ( $GLOBALS["TSFE"]->fe_user->getKey("ses",$this->extKey . "_successfully_submitted") ) {
			return;
		}

			// After successful form submission
		if( $step == $this->lastStep ) {
			$this->sendEmails();
			$this->addNewsletter();

				// remember that we finished
			$GLOBALS["TSFE"]->fe_user->setKey("ses", $this->extKey . "_successfully_submitted", 1);
				// clear caches
			$GLOBALS["TSFE"]->fe_user->setKey("ses", $this->extKey . "_captcha_verified", NULL);
		}
	}

	function sendEmails() {
		// Get Backend-Email-Templates and replace the markers
		$this->substituteValueMarkers(array('templateCode' => $this->data['tx_gokontakt_emailBody'] ));
		$this->substituteLanguageMarkers(array('templateCode' => $this->data['tx_gokontakt_emailBody'] ));
		$this->substituteValueMarkers(array('templateCode' => $this->data['tx_gokontakt_emailAdminBody'] ));
		$this->substituteLanguageMarkers(array('templateCode' => $this->data['tx_gokontakt_emailAdminBody'] ));

		$emailUser = $this->cObj->substituteMarkerArrayCached($this->data['tx_gokontakt_emailBody'], $this->markerArray, $this->subpartMarkerArray, $this->wrappedSubpartMarkerArray);
		$emailUser = str_replace("<br />", "\n", $emailUser);
		$emailAdmin = $this->cObj->substituteMarkerArrayCached($this->data['tx_gokontakt_emailAdminBody'], $this->markerArray, $this->subpartMarkerArray, $this->wrappedSubpartMarkerArray);
		$emailAdmin = str_replace("<br />", "\n", $emailAdmin);
		$emailFrom = $this->data['tx_gokontakt_emailFrom'];
		$emailFromName = $this->data['tx_gokontakt_emailFromName'];
		$emailToAdmin = $this->data['tx_gokontakt_emailToAdmin'];

		$this->sendEmail($this->pi_getLL('subject_email_user'), $emailUser, '', $emailFrom, $emailFromName, $this->piVars['email']);
		$this->sendEmail($this->pi_getLL('subject_email_admin'), $emailAdmin, '', $emailFrom, $emailFromName, $emailToAdmin);
	}

	function sendConfirmationLink($emailTo, $emailFrom, $emailFromName) {
		$this->substituteValueMarkers(array('templateCode' => $this->data['tx_gokontakt_emailNewsletterBody']));
		$this->substituteLanguageMarkers(array('templateCode' => $this->data['tx_gokontakt_emailNewsletterBody'] ));

		$emailFrom = $this->data['tx_gokontakt_emailFrom'];
		$emailFromName = $this->data['tx_gokontakt_emailFromName'];
			// newsletter auth does not use piVars to avoid line breaks in long links
		$this->addMarker('LINK_NEWSLETTER', $GLOBALS['TSFE']->baseUrl . '?id=' . $GLOBALS['TSFE']->id . '&nlAuth=' . $this->newsletterHash . '&u=' . $this->userID);
		$emailUser = $this->cObj->substituteMarkerArrayCached($this->data['tx_gokontakt_emailNewsletterBody'], $this->markerArray, $this->subpartMarkerArray, $this->wrappedSubpartMarkerArray);

		$this->sendEmail($this->pi_getLL('subject_newsletter_confirmation'), $emailUser, '', $emailFrom, $emailFromName, $emailTo);
	}

	function addNewsletter() {
		if ( !$this->newsletterRegEnabled || empty($this->piVars['newsletter']) ) { // only if newsletter was checked
			return;
		}

		$GLOBALS['TYPO3_DB']->exec_INSERTquery( 'fe_users', array(
			'pid' => $this->newsletterPID,
			'deleted' => '0',
			'disable' => '1',
			'tstamp' => time(),
			'crdate' => time(),
			'username' => $this->piVars['email'],
			'usergroup' => 2,
			'title' => $this->piVars['sex'],
			'first_name' => $this->piVars['firstname'],
			'last_name' => $this->piVars['lastname'],
			'address' => $this->piVars['street'] . ' ' . $this->piVars['street_nr'],
			'zip' => $this->piVars['plz'],
			'city' => $this->piVars['city'],
			'company' => $this->piVars['company'],
			'telephone' => $this->piVars['telephone'],
			'fax' => $this->piVars['fax'],
			'email' => $this->piVars['email'] )
		);

		// get the id
		$this->userID = $GLOBALS['TYPO3_DB']->sql_insert_id();

		// get the whole row
		$row = current($GLOBALS['TYPO3_DB']->exec_SELECTgetRows( '*', 'fe_users', 'uid=' . $this->userID ));
		$this->newsletterHash = substr(sha1(serialize($row)), 1, 6); // first 6 letters from hash
		// send the confirmation email
		$this->sendConfirmationLink($this->piVars['email'], $emailFrom, $emailFromName );
	}

	function activateNewsletter() {
			// newsletter auth does not use piVars to avoid line breaks in long links
		$hash = t3lib_div::_GP('nlAuth');
		$userID = t3lib_div::_GP('u');

		// get the whole row
		$row = current($GLOBALS['TYPO3_DB']->exec_SELECTgetRows( '*', 'fe_users', 'uid=' . $userID ));
		$realHash = substr(sha1(serialize($row)), 1, 6); // first 6 letters from hash
		if ( $row['disable'] && ($realHash == $hash) ) { // hash matches
			// enable the user
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery( 'fe_users', 'uid=' . $userID, array(
				'uid' => $userID,
				'disable' => '0' ) );
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * Substitutes ###OPTIONS:xxx### markers for selectboxes by <option>-Tags
	 *
	 * Usually the names and values will be collected from the locallang file
	 * The values are separated from the key by a dot:
	 *
	 * <label index="region.00">(please select)</label>
	 * <label index="region.01">Nordrhein-Westfahlen</label>
	 * <label index="region.02">Niedersachsen</label>
	 *
	 * @param 	key			the marker's key e.g. 'region' for ###OPTIONS:region###
	 * @param	opts		the configuration array
	 *						 - 'listText' => 'value1,value2,value3,
	 *										  +optiongroup 1+,Sub-Option A,Sub-Option B,
	 *										  +optiongroup 2+,Sub-Option C,Sub-Option D'
	 * 							the values come from a comma-separated list
	 *							option groups can be defined by wrapping the value like this: +value+
	 *							if not supplied the values will be collected from the locallang file
	 *						 - 'languageKey': the locallang-Key key that holds the options
	 *							e.g. 'region' for entries like region.01,region.02,region.03
	 *							default: $key (the marker's key name )
	 */
	function substituteOptionMarker($key, $opts = array() ) {
		$defaults = array (
			'listText' => '',
			'languageKey' => $key,
		);
		$opts = array_merge($defaults, $opts);

		$options = '';
		$i = 0;
		if ( empty( $opts['listText'] ) ) {	// get from locallang
				// overlay the selected language onto the default array (merge)
			$locallang = array_merge( $this->LOCAL_LANG['default'], $this->LOCAL_LANG[$this->LLkey]);
			foreach($locallang as $langKey => $text) {
				$markerDot = $opts['languageKey'] . '.';
				if (strpos($langKey, $markerDot) === 0) {
					$value =  substr($langKey, strlen($markerDot));
					$selected = ( $this->piVars[$key] == $value ) ? 'selected="selected"' : '';
					$value = $i ? $value : '';
					$options .= '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>' . chr(13);
					$i++;
				}
			}
		} else {
			$listItems = explode(',', $opts['listText']); // split at new line
			$i = 0;
			// for option groups
			$optgroupOpen = FALSE;
			foreach ( $listItems as $value ) {
				$value = trim($value);
				if ( (substr($value,0,1) == '+') ) { // an optgroup begins and ends with '+' e.g. +option group+
					if ( $optgroupOpen ) { // close last optgroup
						$options .= '</optgroup>';
					}
					$options .= '<optgroup label="' . substr($value,1) . '">';
					$optgroupOpen = TRUE;
				} else {
					$selected = ($this->piVars[$key] == $value) ? 'selected="selected"' : '';
					$text = $value;
					$value = $i ? $value : '';
					$options .= '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>' . chr(13);
					$i++;
				}
			}
			if ( $optgroupOpen ) { // close last optgroup, if there was any
				$options .= '</optgroup>';
			}
		}
		$this->markerArray['###OPTIONS:' . $key . '###'] = $options;
	}


	/**
	 * This substitutes the form markers in the template
	 * ==================================================
	 *  (PIVAR-VALUE and PIVAR-NAME are handled in pibase)
	 *
	 *
	 *  The ERROR-MESSAGE markers may be composed as follows
	 *
	 *    ###ERROR-MESSAGE:field=any###
	 *    ###ERROR-MESSAGE:field=typeOfCheck###
	 *    ###ERROR-MESSAGE:field1,field2=typeOfCheck###
	 *
	 *  markers will be replaced by the locallang-value of the pattern "formError_typeOfCheck"
	 *  (e.g. "formError_obligatory")
	 *
	 *
	 *  The ERROR-CLASS markers may be composed as follows
	 *
	 *    ###ERROR-CLASS:field###
	 *    ###ERROR-CLASS:field1,field2,field3###
	 *
	 *    The ERROR-CLASS markers will be replaced by a number of classes:
	 *	     - formError
	 *	     - formError-name-fieldName
	 *       - formError-type-errorType
	 *    (e.g. "formError formError-name-message and formError-type-obligatory")
	 *
	 *
	 *  The CHECKED markers are composed like this
	 *
	 *    ###CHECKED:sex=male###
	 *
	 *  and will be replaced if the condition in the second part of the marker is true
	 *
	 *
	 * @param array $options		options array
	 * 		['subpartName']			the subpart of $this->template to use, if FALSE use complete template (default)
	 *		['templateCode']		the template code (default is $this->template)
	 *
	 */
	function substituteFormMarkers($options = array()) {
		if(!isset($options['templateCode']) && !$this->template) {
			$this->loadTemplate();
		}
			// configuration array
		$default = array(
			'subpartName' => FALSE,
			'templateCode' => $this->template
		);
		$options = array_merge($default, $options);
		$localTemplate = $options['subpartName'] === FALSE ? $options['subpartCode'] :
						$this->cObj->getSubpart($options['templateCode'], '###' . strtoupper($options['subpartName']) . '###');

		$allowed = array(
			'putTextOnError' => 'ERROR-MESSAGE:',
			'putClassOnError' => 'ERROR-CLASS:',
			'inputFieldChecked' => 'CHECKED:',
		);

		$result = array(array());
			// search for an expression like this ###PREFIX:field### or ###PREFIX:field=errorType###
		preg_match_all('/###(' . implode('|', $allowed) . ')([^=]*|(.*)=(.*))###/iU', $localTemplate, $result);

		foreach($result[0] as $key => $markerValue) {
			switch ($result[1][$key]) {
				case $allowed['putTextOnError']:
						// the field name(s) (paying attention to either ###PREFIX:field### or ###PREFIX:field=errorType###)
					$fieldNames = empty($result[3][$key]) ? $result[2][$key] : $result[3][$key];
						// if errorType is not defined, we catch all errors
					$errorType = isset($result[4][$key]) ? $result[4][$key] : 'any';
					$this->markerArray[$markerValue] = $this->makeErrorText($fieldNames,$errorType,$options);
					break;
				case $allowed['putClassOnError']:
					$fieldNames = $result[2][$key];
					$this->markerArray[$markerValue] = $this->makeErrorCssClass($fieldNames,$options);
					break;
				case $allowed['inputFieldChecked']:
						// fill in if the field is checked or not
					$this->markerArray[$markerValue] = ($this->piVars[$result[3][$key]] == $result[4][$key]) ? 'checked="checked"' : '';
					break;
			}
		}

		return;
	}

	/**
	 * Creates error messages for the matched marker
	 *
	 * @param	string		$fieldNames				the comma separated list of field names
	 * @param	string		$errorType				the error Type
	 * @param	array		$options				the options (inherited from calling function)
	 */
	function makeErrorText( $fieldNames, $errorType, $options ) {
			// split up multiple fields
		$fieldNames = explode(',',$fieldNames);

			// check for each field if there is an error of this type
		$isError = FALSE;
		foreach( $fieldNames as $field ) {
			$isError = $isError || $this->errors[$field][$errorType];
			if ($isError) {
				break;
			}
		}
		$this->addMarker('ERROR_CLASS','formError-type-' . $errorType);
		$this->addMarker('ERROR_MESSAGE',$this->pi_getLL('formError_' . $errorType, '*error label not defined*'));
		$errorLabel = $isError ? $this->parseTemplate('ERROR_MESSAGE_WRAP') : '';
		return $errorLabel;
	}

	/**
	 * Creates error CSS classes for the matched marker
	 *
	 * @param	string		$fieldNames				the comma separated list of field names
	 * @param	array		$options				the options (inherited from calling function)
	 */
	function makeErrorCssClass( $fieldNames, $options ) {
			// split up multiple fields
		$fieldNames = explode(',',$fieldNames);

		$errorClasses = '';
			// create error classes for each error
		foreach( $fieldNames as $field ) {
			$errorClasses .= empty($this->errors[$field]['any']) ? '' : ' formError-name-' . $field;
			foreach( $this->errors[$field] as $errorType => $errorValue ) {
				if ( $errorValue && ($errorType != 'any') ) {
					$errorClasses .= ' formError-type-'.$errorType;
				}
			}
		}
			// add the general error marker (if there was any)
		$errorClasses = empty($errorClasses) ? '' : 'formError' . $errorClasses;
		return $errorClasses;
	}

	/*
	 * Checks for errors in this step and all the preceeding steps of the form
	 *
	 * The Typoscript will be parsed for that reason
	 * Put the error handling configuration into errorCheck.step_X in TS
	 *
	 * plugin.tx_gokontakt_piKontakt.errorCheck {
	 *		...
	 * 		step_1 {
	 * 			firstname = obligatory
	 * 			email = obligatory,email
	 * 			specialField = custom
	 *			specialField.custom.x = 3
	 *			specialField.custom.y = 1
	 *		}
	 *		...
	 *
 	 * Error handlers (as "custom" above) cause user-defined error function to be called.
	 * So make sure to add a function like this for each custom error check (the function returns
	 * TRUE on error and FALSE on success):
	 *
	 *		function checkError_custom($field, $conf) {
	 *			return ($this->piVars[$field] == $conf['x']);
	 *		}
	 *
	 * @param	$step		the step's number
	 */

	function doErrorChecks($step) {
			// check for each preceeding AND for the required step
		for ( $currentStep = 1; $currentStep <= $step; $currentStep++ ) {
				// parse the TS error handlig configuration for each field
			foreach ( $this->conf['errorCheck.']['step_' . $currentStep . '.'] as $field => $errorTypes ) {
				if ( is_array($errorTypes) ) { // it is just a configuration array and not a field definition
					continue;
				}
				$errorTypeArray = t3lib_div::trimExplode(',', $errorTypes);
				foreach ( $errorTypeArray as $errorType ) {
					// error handlers will be called here
					$this->errors[$field][$errorType] = $this->callErrorHandler($field, $errorType, $this->conf['errorCheck.']['step_' . $step . '.'][$field . '.'][$errorType . '.']);
				}
			}
		}
		// insert the 'any'-indicator, that tells us if the field has any error at all
		$anyError = 0;
		foreach ( $this->errors as $field => $errorTypes ) {
			$this->errors[$field]['any'] = 0;
			foreach ( $errorTypes as $errorType => $value ) {
				if ( $value ) {
					$this->errors[$field]['any'] = 1;
					$anyError = 1;
						// skip the rest, we already found an error
					break;
				}
			}
		}
		$this->errors['any'] = $anyError;
	}

	/**
	 * Calls the error handler functions dynamically
	 *
	 * @param	$field			the name of the field to be checked
	 * @param	$errorType		the type of the error check (will result in the function's name)
	 * @param	$errorConf		the TS config of this error check type
	 */
	function callErrorHandler($field,$errorType,$errorConf) {
		if ( method_exists($this, $methodName = 'checkError_' . $errorType) ) {
			return call_user_func( array(&$this, $methodName), $field, $errorConf ) ? 1 : 0;
		} else {
			return 1;
		}
	}

	/*
	 * The predefined error handlers ...
	 *
	 * @param $field		the form field's name
	 * @param $conf			the TS configuration of that error type
	 */
	function checkError_obligatory($field, $conf) {
	 	if ( is_array($this->piVars[$field]) ) {
			return (count( $this->piVars[$field] ) == 0);
		} else {
			return (strlen(trim( $this->piVars[$field] )) == 0);
		}
	}

	function checkError_nonezero($field, $conf) {
	 	if ( is_array($this->piVars[$field]) ) {
			return (count( $this->piVars[$field] ) == 0);
		} else {
			return (floatval(trim( $this->piVars[$field]))==0);
		}
	}

	function checkError_email($field, $conf) {
	 	return !t3lib_div::validEmail( trim( $this->piVars[$field] ) );
	}

	function checkError_zip($field, $conf) {
	 	return preg_match ("/^[0-9]{5}/", trim( $this->piVars[$field] ));
	}

	function checkError_captcha($field, $conf) {
		if ( !$this->conf['useSpamProtection'] || $this->captchaVerified() ) {
				// skip if no check necessary
			return FALSE;
		}
		if ( !t3lib_div::_GP("recaptcha_challenge_field") || !t3lib_div::_GP("recaptcha_response_field") ) {
				// if not filled in
			return TRUE;
		}

		$resp = recaptcha_check_answer( $conf['privateKey'],
				t3lib_div::getIndpEnv('REMOTE_ADDR'),
				t3lib_div::_GP("recaptcha_challenge_field"),
				t3lib_div::_GP("recaptcha_response_field") );

		if ( $resp->is_valid ) {
				// if already successfully entered OR valid
			$GLOBALS["TSFE"]->fe_user->setKey("ses", $this->extKey . "_captcha_verified", 1);
			return FALSE;
		} else {
				// error
			return TRUE;
		}
	}

	/*
	 * The custom error handlers go here ...
	 */



	/*
	* Store all piVar Data in the session and merges the
	* session back to the piVars
	*
	*/
	function mergePiVarsWidthSession() {
			// get session data
		$GLOBALS["TSFE"]->fe_user->fetchSessionData();
		$values = $GLOBALS["TSFE"]->fe_user->sesData[$this->extKey];

			// merge with piVars (piVars override session)
		$values = array_merge( isset($values) ? $values : array(), $this->piVars);

			// Update session & piVars
		$GLOBALS["TSFE"]->fe_user->setKey("ses", $this->extKey, $values);
		$GLOBALS["TSFE"]->fe_user->fetchSessionData();
		$this->piVars = $values;
	}

	/*
	 * Checks if the captcha has already been verified
	 */

	function captchaVerified() {
		$GLOBALS["TSFE"]->fe_user->fetchSessionData();
		return ((int) $GLOBALS["TSFE"]->fe_user->sesData[$this->extKey . "_captcha_verified"]) ? TRUE : FALSE;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_kontakt/piKontakt/class.tx_gokontakt_piKontakt.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_kontakt/piKontakt/class.tx_gokontakt_piKontakt.php']);
}

?>