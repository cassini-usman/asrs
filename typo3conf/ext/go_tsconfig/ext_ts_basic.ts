# Environment
config.doctype = xhtml_trans
config.spamProtectEmailAddresses = 1
config.spamProtectEmailAddresses_atSubst = (at)
config.disablePrefixComment = true
config.xhtml_cleaning = all

config.extTarget = _blank


# Cache configuration
config.sendCacheHeaders = 1
config.cache_period = 86400


# realurl
config.simulateStaticDocuments = 0
config.tx_realurl_enable = 1


# Page configuration
page = PAGE
page.includeCSS.base_fest = fileadmin/templates/css/base_fest.css
page.includeCSS.top_menu_with_layers = fileadmin/templates/css/top_menu_with_layers.css
page.includeCSS.left_menu = fileadmin/templates/css/left_menu.css
page.includeCSS.content = fileadmin/templates/css/content.css
page.includeCSS.contentteiler = fileadmin/templates/css/contentteiler.css
page.includeCSS.footer_menu = fileadmin/templates/css/footer_menu.css

[useragent = *iPad*]
	page.includeCSS.iPad = fileadmin/templates/css/iPad.css
	page.bodyTagAdd = class="ipad"
[global]

[browser = msie] && [system = win] && [version = 7]
	page.includeCSS.ie7 = fileadmin/templates/css/ie7.css
	page.bodyTagAdd = class="ie7"
[global]

[browser = msie] && [system = win] && [version = 8]
	page.includeCSS.ie8 = fileadmin/templates/css/ie8.css
	page.bodyTagAdd = class="ie8"
[global]

[browser = msie] && [system = win] && [version = 9]
	page.includeCSS.ie9 = fileadmin/templates/css/ie9.css
	page.bodyTagAdd = class="ie9"
[global]

[browser = chrome] && [system = win]
	page.includeCSS.chrome_win = fileadmin/templates/css/chrome_win.css
	page.bodyTagAdd = class="chrome_win"
[global]

[browser = chrome] && [system = mac]
	page.includeCSS.chrome_mac = fileadmin/templates/css/chrome_mac.css
	page.bodyTagAdd = class="chrome_mac"
[global]

[useragent = *Firefox*] && [system = win]
	page.includeCSS.firefox_win = fileadmin/templates/css/firefox_win.css
	page.bodyTagAdd = class="firefox_win"
[global]

[useragent = *Firefox*] && [system = mac]
	page.includeCSS.firefox_mac = fileadmin/templates/css/firefox_mac.css
	page.bodyTagAdd = class="firefox_mac"
[global]

[useragent = *Safari*] && [system = win]
	page.includeCSS.safari_win = fileadmin/templates/css/safari_win.css
	page.bodyTagAdd = class="safari_win"
[global]

[useragent = *Safari*] && [system = mac]
	page.includeCSS.safari_mac = fileadmin/templates/css/safari_mac.css
	page.bodyTagAdd = class="safari_mac"
[global]

# includeJS libs fileadmin/templates/javascript/..
page.includeJS.jQuery = fileadmin/templates/javascript/jquery.js
page.includeJS.jQueryCookie = fileadmin/templates/javascript/jquery.cookie.js

# Add page favicon
page.headerData.10 = TEXT
page.headerData.10.value (
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

)


# Include Templavoila
# Note that you should not use page.20, as it is reserved for Queo-Speedup
page.10 = USER
page.10.userFunc = tx_templavoila_pi1->main_page



page.content.RTE_compliant = 0


# Page metadata configuration
page.meta {
	keywords = TEXT
	keywords.data = DB:pages:3:keywords
	keywords.override {
		required = 1
		data = field:keywords
	}

	description = TEXT
	description.data = DB:pages:3:description
	description.override {
		required = 1
		data = field:description
	}

	abstract = TEXT
	abstract.data = DB:pages:3:abstract
	abstract.override {
		required = 1
		data = field:abstract
	}

	robots = TEXT
	robots.data = DB:pages:3:robots
	robots.override {
		required = 1
		data = field:robots
	}

	revisit-after = 10 days
	author = Gosign media., Hamburg, Germany
}

# Adds a prefix to typo3's temporary file names (md5 hashes)
# so they can be easily identified.
# e.g. 4d3f00c.jpg -> flower_4d3f00c.jpg
config.meaningfulTempFilePrefix = 100

# add div wrap for CType text
tt_content.text.20.wrap = <div class="csc-textpic-text"> | </div>

# Javascript Check for CompatMode
# page.headerData.38 = TEXT
# page.headerData.38.value = <script> alert(document.compatMode); </script>
