#
# Table structure for table 'tx_gobackendlayout_fieldrights'
#
CREATE TABLE tx_gobackendlayout_fieldrights (
	uid int(11) unsigned NOT NULL auto_increment,
	fieldName tinytext,
	elementKey tinytext,
	deleted tinyint(3) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid)
);
