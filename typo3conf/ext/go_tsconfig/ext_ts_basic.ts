
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


# Baseurls
config.baseURL = http://projects.dev.gosign.de/current/typo3_4-5_default/
config.baseURL = http://ipod.local:8888/project/htdocs/
[globalString = IENV:HTTP_HOST= *CHANGEME]
    config.baseURL = http://CHANGEME
[global]


# Page configuration
page = PAGE
page.includeCSS.base_fest = fileadmin/templates/css/base_fest.css
page.includeCSS.top_menu_with_layers = fileadmin/templates/css/top_menu_with_layers.css
page.includeCSS.left_menu = fileadmin/templates/css/left_menu.css
page.includeCSS.content = fileadmin/templates/css/content.css
page.includeCSS.contentteiler = fileadmin/templates/css/contentteiler.css
page.includeCSS.footer_menu = fileadmin/templates/css/footer_menu.css

[useragent = *Mozilla*] && [system = mac]
	page.includeCSS.firefox_mac = fileadmin/templates/css/firefox_mac.css
[global]

[useragent = *Safari*] && [system = mac]
	page.includeCSS.safari_mac = fileadmin/templates/css/safari_mac.css
[global]

# includeJS libs fileadmin/templates/javascript/..
page.includeJS.jQuery = fileadmin/templates/javascript/jquery.js
page.includeJS.jQueryCookie = fileadmin/templates/javascript/jquery.cookie.js
page.includeJS.GoLib = fileadmin/templates/javascript/goLib.js

# Add page favicon
page.headerData.10 = TEXT
page.headerData.10.value (
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

)


# Include Templavoila
page.10 = USER
page.10.userFunc = tx_templavoila_pi1->main_page


# QueoSpeedup configuration
[globalString = IENV:HTTP_HOST= *dev.gosign.de] && [globalVar = GP:sol > 0]
  page.20 =< plugin.tx_queospeedup_pi1
[else]
  [globalString = IENV:HTTP_HOST= *dev.gosign.de] || [globalVar = GP:dd > 0]
  [else]
    page.20 =< plugin.tx_queospeedup_pi1
[global]


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


# Mansoor Ahmad - Change the headerparsesing
lib.stdheader.stdWrap.br = 1

# Mansoor Ahmad - Delete Default Anchor wrap
tt_content.stdWrap.innerWrap >
tt_content.stdWrap.prepend >


# Javascript Check for CompatMode
#page.headerData.38 = TEXT
#page.headerData.38.value = <script> alert(document.compatMode); </script>


# Adds a prefix to typo3's temporary file names (md5 hashes)
# so they can be easily identified.
# e.g. 4d3f00c.jpg -> flower_4d3f00c.jpg
config.meaningfulTempFilePrefix = 100