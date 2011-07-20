$(document).ready(function(){
	showHideNewsletterBox(0);
	$('.kontaktErweitert #newsletter').change( showHideNewsletterBox );
	
	function showHideNewsletterBox(time) {
		if ( $('.kontaktErweitert #newsletter:checkbox:checked').length ) {
			$('.kontaktErweitert .newsletterBox').slideDown(time);
		} else {
			$('.kontaktErweitert .newsletterBox').slideUp(time);
		}
	}
});
