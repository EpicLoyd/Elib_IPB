<?php
if (!defined('IN_IPB'))
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}
$this->registry->class_localization->loadLanguageFile( array( 'public_lang' ), 'elib' );

$CONFIG['plugin_name']	   = 'Minecraft';
$CONFIG['plugin_lang_bit'] = 'minecraft_profile_tab_title';
$CONFIG['plugin_key']	   = 'minecraft';
$CONFIG['plugin_enabled']  = 1;
$CONFIG['plugin_order']    = 6;
