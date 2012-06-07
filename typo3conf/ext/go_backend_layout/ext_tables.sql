#
# Table structure for table 'tx_gobackendlayout_fieldrights'
#
CREATE TABLE tx_gobackendlayout_fieldrights (
	uid int(11) unsigned NOT NULL auto_increment,
	fieldName tinytext,
	templateObject int(11) DEFAULT '0' NOT NULL,
	elementKey tinytext,
	access tinytext,
	PRIMARY KEY (uid)
);
