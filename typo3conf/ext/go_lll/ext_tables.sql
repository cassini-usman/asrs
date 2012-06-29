#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_golll_labelcontainer text NOT NULL,
	tx_golll_ctype tinytext NOT NULL,
	tx_golll_sorting tinyint(4) DEFAULT '0' NOT NULL
	tx_golll_searchstring varchar(64) DEFAULT '' NOT NULL,
);

#
# Table structure for table 'tx_golll_translation'
#
CREATE TABLE tx_golll_translation (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	parentElement int(11) DEFAULT '0' NOT NULL,
	tx_golll_ctype varchar(64) DEFAULT '' NOT NULL,
	tx_golll_label varchar(64) DEFAULT '' NOT NULL,
	tx_golll_value tinytext NOT NULL,
	tx_golll_langlabel varchar(32) DEFAULT '' NOT NULL,
	PRIMARY KEY (uid),
	UNIQUE INDEX ctypeLabelLang (tx_golll_ctype,tx_golll_label,tx_golll_langlabel),
	KEY parent (pid),
	KEY tx_golll_ctype (tx_golll_ctype),
	KEY tx_golll_label (tx_golll_label),
	KEY tx_golll_langlabel (tx_golll_langlabel)
);