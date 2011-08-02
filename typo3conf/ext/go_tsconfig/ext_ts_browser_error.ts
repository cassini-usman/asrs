[browser = msie] && [system = win] && [version = <8]
	ie6error = HTML
	ie6error.value(
		<div class="ie6error" style="width:596px;margin:auto;">
			<map name="mapie6" id="mapie6">
			   <area href="http://www.mozilla-europe.org/de/firefox/" target="_blank" alt="Firefox" title="Firefox" shape="rect" coords="9,50,171,98" />
			   <area href="http://www.google.com/chrome/" target="_blank" alt="Google Chrome" title="Google Chrome" shape="rect" coords="211,59,400,92" />
			   <area href="http://www.microsoft.com/windows/internet-explorer/" target="_blank" alt="Internet Explorer" title="Internet Explorer" shape="rect" coords="439,51,595,95" />
			</map>
			<img src="typo3conf/ext/go_tsconfig/res/browser_error.png" alt="Browser Error" usemap="#mapie6" />
		</div>
	)
	page.5 < ie6error
[global]
