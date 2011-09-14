<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'piKontakt/class.tx_gokontakt_piKontakt.php', '_piKontakt', 'CType', 0);


?>