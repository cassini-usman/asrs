# Add https, if SSL is on
# See http://proggiwiki.gosign.de/doku.php?id=technologies:typo3:ssl for
# additional information.
[globalVar = IENV:TYPO3_SSL = 1]
    config.baseURL := replaceString(http|https)
[global]


# QueoSpeedup configuration
# These conditions enable the Queo-Speedup extension by default on our
# main development server (Loki), and disable it everywhere else.
# The GET-Parameter "queo" may be used to force-disable (?queo=0) or
# force-enable (?queo=1) the extension.
# You may use the "config.enableQueoSpeedup" variable to enable Queo-Speedup
# by default on any system.
[globalString = IENV:HTTP_HOST= *dev.gosign.de] || [globalVar = GP:queo = 1]
	config.enableQueoSpeedup = 1
[end]

[globalVar = GP:queo = 0]
	config.enableQueoSpeedup = 0
[end]

# Queo-Speedup is packed into a COA as a workaround, because USER_INT does not
# support the "if.*" TypoScript condition properties.
page.20 = COA
page.20 {
	10 =< plugin.tx_queospeedup_pi1
	if.value < config.enableQueoSpeedup
	if.equals = 1
}
