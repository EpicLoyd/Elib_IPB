<?php

/**
 * Product Title:		Shoutbox
 * Author:				IPB Works
 * Website URL:			http://www.ipbworks.com/forums
 * Copyright©:			IPB Works All rights Reserved 2011-2012
 */

// Tables
$TABLE[] = "CREATE TABLE shoutbox_shouts (
	s_id int(11) NOT NULL auto_increment,
	s_mid int(11) NOT NULL,
	s_date int(11) NOT NULL,
	s_message text NOT NULL,
	s_ip varchar(32) NOT NULL,
	s_edit_history text NULL,
	PRIMARY KEY  (s_id),
	KEY (s_mid),
	KEY (s_date)
)";

$TABLE[] = "CREATE TABLE shoutbox_mods (
	m_id int(11) NOT NULL auto_increment,
	m_type VARCHAR(6) NOT NULL default '',
	m_mg_id INT(11) NOT NULL default '0',
	m_edit_shouts tinyint(1) NOT NULL default '1',
	m_delete_shouts tinyint(1) NOT NULL default '1',
	m_delete_shouts_user tinyint(1) NOT NULL default '0',
	m_ban_members tinyint(1) NOT NULL default '0',
	m_unban_members tinyint(1) NOT NULL default '0',
	m_remove_mods tinyint(1) NOT NULL default '0',
	PRIMARY KEY (m_id)
)";

// Alter Tables
$TABLE[] = "ALTER TABLE members ADD serverId VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD accessToken VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD sessionId VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD md5 VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD banned INT(1) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD money VARCHAR(64) NOT NULL DEFAULT '0';";