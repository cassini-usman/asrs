(function(jQuery) {
	var object;

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-06-26
	 *  @desc:		This function fetches the infos from the hidden input fields
	 *  @param:		void
	 *
	 *  @return:	Array	array with notes objects
	 */

	function fetchNotes() {
		var singleNotesContainer = jQuery('.singleNote', object);
		var notes = [];
		singleNotesContainer.each(function(){
			var self = jQuery(this);
			temp = {
				'id': jQuery('.noteId', self).val(),
				'text': jQuery('.text', self).val(),
				'pos_x': jQuery('.posX', self).val(),
				'pos_y': jQuery('.posY', self).val(),
				'width': jQuery('.width', self).val(),
				'height': jQuery('.height', self).val()
			};
			notes.push(temp);
		});

		return notes;
	}

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-06-26
	 *  @desc:		This function creates a stickyNotes object
	 *  @param:		void
	 *
	 *  @return:	boolean - false
	 */

	function main() {
		var fetchedNotes = fetchNotes();
		if (!jQuery.isEmptyObject(object)) {
			object.stickyNotes({
				notes: fetchedNotes,
				createCallback: function(note) {
					jQuery.post(location + '&isAjax=1', {
						action: 'createNote',
						noteId: note.id,
						noteWidth: note.width,
						noteHeight: note.height
					}, function(data){}, "json");
				},
				editCallback: function(note) {
					jQuery.post(location + '&isAjax=1', {
						action: 'editNote',
						noteId: note.id,
						noteText: note.text
					}, function(data){}, "json");
				},
				deleteCallback: function(note) {
					jQuery.post(location + '&isAjax=1', {
						action: 'deleteNote',
						noteId: note.id
					}, function(data){}, "json");
				},
				resizeCallback: function(note) {
					jQuery.post(location + '&isAjax=1', {
						action: 'resizeNote',
						noteId: note.id,
						noteWidth: note.width,
						noteHeight: note.height
					}, function(data){}, "json");
				},
				moveCallback: function(note) {
					jQuery.post(location + '&isAjax=1', {
						action: 'moveNote',
						noteId: note.id,
						notePosX: note.pos_x,
						notePosY: note.pos_y
					}, function(data){}, "json");
				}
			});
		}
	}

	/**
	 *  @author:	Daniel Agro <agro@gosign.de>
	 *  @date:		2012-07-26
	 *  @desc:		This function checks if we are in the page module, initializes the globar var
	 *				and binds the keydown event
	 *  @param:		jQuery object to check
	 *
	 *  @return:	boolean - true if object to check exists
	 */
	function init(obj) {
		if (obj.length > 0) {
			object = obj;

			jQuery(document).keydown(function(e){
				if(e.which == 110 || e.which == 78) {
					jQuery('#notes').css({'height': jQuery('#typo3-docbody').height() + 'px'});
					jQuery('#notes').css({'display': 'block'});
				} else if (e.which == 27) {
					jQuery('#notes').css({'display': 'none'});
				}
			});

			return true;
		}
		return false;
	}

	jQuery(document).ready( function() {
		if(init(jQuery('#notes'))) {
			main();
		}
	});
})(jQuery);