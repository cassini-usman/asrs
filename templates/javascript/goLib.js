var GOSIGN = (function(){
	/**
	 * returns all GET Parameters as an Array
	 */
	function getUrlVars(){
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}
	
	/**
	 * returns value of the GET parameter 'name'
	 */
	function getUrlVar(name){
		return $.getUrlVars()[name];
	}
	
	/**
	 * returns true if 'data' starts with 'str'
	 */
	function startsWith(data, str){
		return (data.match("^"+str)==str);
	}
	
	/**
	 * returns the shuffeled array
	 */
	function shuffle(arr) {
		for(
		  var j, x, i = arr.length; i;
		  j = parseInt(Math.random() * i),
		  x = arr[--i], arr[i] = arr[j], arr[j] = x
		);
		return arr;
	}
	
	/**
	 * random shuffeled Array will be read cyclicly
	 * returns next elem from cookie
	 */
	function shuffleArrayCookie(conf){
		var cookieVals = conf.key + '_vals';
		var cookieIndex = conf.key + '_index';
		
		if(!$.cookie(cookieVals) || conf.id != $.cookie(conf.key) || !$.cookie(cookieIndex)){
			$.cookie(cookieVals,  shuffle(conf.data),  { expires: 1, path: '/' });
			$.cookie(cookieIndex,  0,  { expires: 1, path: '/' });
			$.cookie(conf.key,  conf.id,  { expires: 1, path: '/' });
		}
		
		var values = $.cookie(cookieVals).split(',');
		$.cookie(cookieIndex, ( ( parseInt( $.cookie(cookieIndex) ) + 1 ) % values.length ),  { expires: 1, path: '/' });
		var index = $.cookie(cookieIndex);
		return values[index];
	}
	
	
	return {
		getUrlVar:getUrlVar,
		startsWith:startsWith,
		shuffleArrayCookie:shuffleArrayCookie
	}
})();