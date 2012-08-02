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


#
# Table structure for table 'tx_gobackendlayout_notes'
#
CREATE TABLE tx_gobackendlayout_notes (
	uid int(11) unsigned NOT NULL auto_increment,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	noteId int(11) unsigned DEFAULT '0' NOT NULL,
	pageId int(11) unsigned DEFAULT '0' NOT NULL,
	text text,
	posX int(11) unsigned DEFAULT '0' NOT NULL,
	posY int(11) unsigned DEFAULT '0' NOT NULL,
	width int(11) unsigned DEFAULT '0' NOT NULL,
	height int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(3) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid)
);