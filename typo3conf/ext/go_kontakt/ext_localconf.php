<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'piErweitert/class.tx_gokontakt_piErweitert.php', '_piErweitert', 'CType', 0);
t3lib_extMgm::addPItoST43($_EXTKEY, 'piNormal/class.tx_gokontakt_piNormal.php', '_piNormal', 'CType', 0);


?>