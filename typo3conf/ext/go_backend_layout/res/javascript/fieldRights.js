(function(jQuery) {
	var object;
	var hiddenInfos;
	var fieldName;

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-06-05
	 *  @desc:		This function binds onClick events to the checkboxes which handle the checking and unchecking of the checkboxes
	 *  @param:		void
	 *
	 *  @return:	boolean - false
	 */

	function main() {
		var checkBoxes = jQuery('input[type=checkbox]', object);

		checkBoxes.click(function(){
			var self = jQuery(this);
			var templateObject = self.val();

			var cType = self.attr('name');
			var checkInfo = self.is(':checked')

			jQuery.ajax({
				url: window.location,
				type: 'POST',
				data: 'isAjax=1&action=rightsAction&field=' + fieldName + '&cType=' + cType + '&templateObject=' + templateObject + '&check=' + checkInfo,
				context: document.body,
				success: function(data, textStatus, jqXHR){
					return false;
				}
			});
		});
	}

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-06-05
	 *  @desc:		This function fetches the cType from the create record link
	 *  @param:		Content element to fetch the cType from
	 *
	 *  @return:	string - cType
	 */
	function getCEParameter(self, searchfor) {
		var searchString = jQuery('a', self).attr('href');
		var params = searchString.split("&");
		var i, val;

		for (i = 0; i < params.length; i++) {
			val = params[i].split("=");
			if (unescape(val[0]) == searchfor) {
				return unescape(val[1]);
			}
		}
		return null;
	}

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-06-05
	 *  @desc:		This function fetches the hidden info about the checkbox,
	 *				initilizes the checkbox (value) and set it check or unchecked
	 *				depend on the hidden info
	 *  @param:		Content element whose checkbox has to be initialized
	 *
	 *  @return:	void
	 */
	function initCheckbox(self) {
		var cType = getCEParameter(self, 'defVals[tt_content][CType]');
		var templateObject = getCEParameter(self, 'defVals[tt_content][tx_templavoila_to]');
		var templateObject = templateObject ? templateObject : '0';

		var checkbox = jQuery('input.fieldrightsCheckbox', self);
		checkbox.val(templateObject);
		checkbox.attr('name', cType);

		var hiddenInfo = jQuery('.' + cType + '_' + templateObject + ' input', hiddenInfos).attr('checked');
		if(hiddenInfo == 'checked') {
			checkbox.attr('checked', true);
		} else {
			checkbox.attr('checked', false);
		}
	}

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-06-05
	 *  @desc:		This function fetches all listet CE and calls initCheckbox()
	 *				to initialize the checkboxes
	 *  @param:		void
	 *
	 *  @return:	void
	 */
	function initCheckboxes() {
		var tabContainer = jQuery('.c-tablayer', object);

		tabContainer.each(function(){
			var self = jQuery(this);
			var wizardItems = jQuery('tr.row', self);
			wizardItems.each(function(){
				initCheckbox(jQuery(this));
			});
		});
	}

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-06-05
	 *  @desc:		This function checks if we are in the wizard, initializes the globar vars
	 *				and calls initCheckboxes()
	 *  @param:		jQuery object to check
	 *
	 *  @return:	boolean - true if initialized is successfully
	 */
	function init(obj) {
		if (obj.length > 0) {
			object = obj;
			hiddenInfos = jQuery('.hiddenInfos');
			fieldName = jQuery('.fieldName', hiddenInfos).val();
			initCheckboxes();
			return true;
		}
		return false;
	}

	jQuery(document).ready( function() {
		if(init(jQuery('.typo3-dyntabmenu-divs'))) {
			main();
		}
	});
})(jQuery);