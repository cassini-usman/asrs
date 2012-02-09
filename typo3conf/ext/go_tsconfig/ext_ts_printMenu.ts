page.includeCSS {
	printCss = fileadmin/templates/css/print.css
	printCss.media = print
}

[globalVar = GP:print = 1]
	page.includeCSS.printView = fileadmin/templates/css/print.css
	page.includeJS.printView = fileadmin/templates/javascript/print.js
[global]

printmenu = TEXT
printmenu.value = printmenu