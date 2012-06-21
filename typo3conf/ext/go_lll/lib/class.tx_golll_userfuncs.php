<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011   Daniel Agro <agro@gosign.de>
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

/**
 * lib with userfuncs for TCA
 *
 * @author Marius Stuebs <marius@gosign.de>
 * @author Daniel Agro <agro@gosign.de>
 *
 * @package	TYPO3
 * @subpackage	tx_golll
 */
class tx_golll_userfuncs {

	var $go_lllBElib;

	/**
	 * Format the list of CTypes that are allowed for Translation
	 * NEEDED BY TCA.
	 *
	 * @author Daniel Agro <agro@gosign.de>
	 *
	 * @date: 2012-06-19
	 *
	 * @param	array	$data the data array as reference
	 * @return	void
	 */
	function getCTypeItems(&$data){
		$this->go_lllBElib = t3lib_div::makeInstance('tx_golll_belib');
			// fetch list of allowed items
		$wizardItems = $this->go_lllBElib->getWizardItems();

			// generate item list
		foreach($wizardItems as $key => $item) {
			$label = $item['title'];
			$data['items'][] = array( $label, $key);
		}
	}

	/**
	 * This function formats the title for the label
	 *
	 * @author	Daniel Agro <agro@gosign.de>
	 * @author	Marius Stübs <marius@gosign.de>
	 *
	 * @date: 2012-02-17
	 *
	 * @param	array	$params array with table, row and title
	 * @return	void
	 */
	function getLabelTitle(&$params, &$null){
		$label = '';

		$seperator = ': ';
		$valueWraps = array( '<span class="tx-golll-value" style="display: block; width: 500px; height: 14px; overflow: hidden;">', '</span>');
		$labelWraps = array( '<span class="tx-golll-label" style="float: right;">(', ')</span>');
		$allWraps = array( '<span class="tx-golll-all" style="display:block;">', '</span>');

		$label .= $valueWraps[0] . $params['row']['tx_golll_value'] . $valueWraps[1];
		$label .= $labelWraps[0] . $params['row']['tx_golll_langlabel'] . $seperator . $params['row']['tx_golll_label'] . $labelWraps[1];

		$params['title'] = $label;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/lib/class.tx_golll_userfuncs.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/go_lll/lib/class.tx_golll_userfuncs.php']);
}


?>