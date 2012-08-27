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
	page.bodyTagAdd = class="ipad"
[global]

[browser = msie] && [system = win] && [version = 7]
	page.bodyTagAdd = class="ie7"
[global]

[browser = msie] && [system = win] && [version = 8]
	page.bodyTagAdd = class="ie8"
[global]

[browser = msie] && [system = win] && [version = 9]
	page.bodyTagAdd = class="ie9"
[global]

[browser = chrome] && [system = win]
	page.bodyTagAdd = class="chrome_win"
[global]

[browser = chrome] && [system = mac]
	page.bodyTagAdd = class="chrome_mac"
[global]

[useragent = *Firefox*] && [system = win]
	page.bodyTagAdd = class="firefox_win"
[global]

[useragent = *Firefox*] && [system = mac]
	page.bodyTagAdd = class="firefox_mac"
[global]

[useragent = *Safari*] && [system = win]
	page.bodyTagAdd = class="safari_win"
[global]

[useragent = *Safari*] && [system = mac]
	page.bodyTagAdd = class="safari_mac"
[global]

# includeJS libs fileadmin/templates/javascript/..
page.includeJS.jQuery = fileadmin/templates/javascript/jquery.js
page.includeJS.jQueryCookie = fileadmin/templates/javascript/jquery.cookie.js

# Add page favicon
page.shortcutIcon = fileadmin/templates/images/favicon.ico


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

	# google webmaster tool for t3admin@gosign.de
	google-site-verification = w1Wpv17tpRO-bHuGYtDkDrsptrkxm1KiC0a2Kl5WHlQ
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
