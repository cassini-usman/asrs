#
# Table structure for table 'gbl_fieldrights'
#
CREATE TABLE gbl_fieldrights (
	uid int(11) unsigned NOT NULL auto_increment,
	fieldName tinytext,
	elementKey tinytext,
	deleted tinyint(3) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid)
);

#
# Table structure for table 'gbl_notes'
#
CREATE TABLE gbl_notes (
	uid int(11) unsigned NOT NULL auto_increment,
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