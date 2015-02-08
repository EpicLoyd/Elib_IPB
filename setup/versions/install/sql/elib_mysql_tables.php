<?php

// Tables
$TABLE[] = "CREATE TABLE IF NOT EXIST iConomy (
	id int(255) NOT NULL auto_increment,
	username varchar(32) NOT NULL,
	balance double(64,2) NOT NULL,
	status int(2) NOT NULL,
)";


// Alter Tables
$TABLE[] = "ALTER TABLE members ADD serverId VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD accessToken VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD sessionId VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD md5 VARCHAR(64) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD banned INT(1) NOT NULL DEFAULT '0';";
$TABLE[] = "ALTER TABLE members ADD money VARCHAR(64) NOT NULL DEFAULT '0';";