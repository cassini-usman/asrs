/**
 * This JavaScript library contains methods often used for this specific
 * project (in contrast to goLib_global.js in the go_pibase extensions, which
 * is used for methods used often throughout all projects). This file is reviewed
 * at the end of a project in order to determine which methods should be included
 * in goLib_global.js
 */

var GosignLocal;

;(function($) {
	GosignLocal = {

		/**
		 * Example method.
		 */
		foo: function() {
			alert("bar");
		}

	};
})(jQuery);

/*
 * GosignLocal tries to be aware of wether it is loaded before or after the
 * goLib_global.js file to prevent errors. However, it will send a console
 * message in case the wrong order is configured (in that case, go_pibase should)
 * be loaded before go_tsconfig).
 */
if(typeof Gosign === "undefined") {
	Gosign = GosignLocal;
	console.log("goLib_local.js: go_pibase seems be loaded after go_tsconfig.");
	console.log("This should not be the case.");
} else {
	Gosign.extend(GosignLocal);
}