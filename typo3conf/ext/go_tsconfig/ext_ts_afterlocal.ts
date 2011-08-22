# Add https, if SSL is on
# See http://proggiwiki.gosign.de/doku.php?id=technologies:typo3:ssl for
# additional information.
[globalVar = IENV:TYPO3_SSL = 1]
    config.baseURL := replaceString(http|https)
[global]