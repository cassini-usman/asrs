<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


t3lib_div::loadTCA("tt_content");
$tempColumns = array();
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);



?>