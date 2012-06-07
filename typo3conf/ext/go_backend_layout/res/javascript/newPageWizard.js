jQuery(document).ready( function () {
	var doktypeSelectBox = jQuery('.doktypeBox select');
	var newTemplateObjectRows = jQuery('.doktypeBox').siblings('table').find('input[value!=0]').closest('table').parent('td');

	doktypeSelectBox.change( function() {
		/*
		 * @TODO: Look at TCA, if the template object is shown in backend and show/hide the selector due to that info.
		 *
		 * At this moment only for 'doktype = 1' the template selection is shown.
		 */
		if( doktypeSelectBox.val() == 1 ) {
			newTemplateObjectRows.show();
		} else {
			newTemplateObjectRows.hide();
		}
	});
});
