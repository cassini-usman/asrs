$(document).ready( function() {
	/**
	 * Sending AJAX Request for fliedrights
	 *
	 * @author: Mansoor Ahmad
	 */
	jQuery('input.fieldrightsCheckbox').click(function(){
		//alert('test');
		var fieldName = $(this).attr('name');
		var elementKey = $(this).attr('value');

		var loc = "";
		if(window.location.search == ""){
			loc = location+'?type=ajax';
		}
		else{
			loc = location+'&type=ajax';
		}

		$.post(loc,{fieldName:fieldName,elementKey:elementKey},function(data){

		},"json");
	});
});