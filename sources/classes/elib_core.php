<?php
 if(!defined('IN_IPB'))
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}


class class_elib_core{
	protected $registry;
	protected $DB;
	protected $settings;
	protected $request;
	protected $member;
	protected $Elib_MCDB;
	protected $Elib_JKDB;
	
	public function __construct( ipsRegistry $registry ){
		$this->registry   = $registry;
		$this->DB         = $this->registry->DB();
		$this->settings   =& $this->registry->fetchSettings();
		$this->cache      =  $this->registry->cache();
		$this->caches     =& $this->registry->cache()->fetchCaches();
		$this->request    =& $this->registry->fetchRequest();
		$this->member     = $this->registry->member();
		$this->memberData =& $this->registry->member()->fetchMemberData();
		IPSDebug::fireBug( 'info', array( $this->settings, "IP.Board Settings Cache" ) ) ;
		
		//Init Elib Databases...
		if ($this->settings['elib_settings_mc_remotedb']){
			$this->Elib_MCDB = new db_driver_mysql;
			$this->Elib_MCDB->obj['sql_user'] = $this->settings['elib_settings_mc_remotedb_login'];
			$this->Elib_MCDB->obj['sql_pass'] = $this->settings['elib_settings_mc_remotedb_password'];
			$this->Elib_MCDB->obj['sql_host'] = $this->settings['elib_settings_mc_remotedb_host'];
			$this->Elib_MCDB->obj['sql_database'] = $this->settings['elib_settings_mc_remotedb_database'];
			$this->Elib_MCDB->obj['force_new_connection']	= 1;
			$this->Elib_MCDB->obj['sql_tbl_prefix'] = '';
			$this->Elib_MCDB->obj['persistent']	 = 1;
			$this->Elib_MCDB->connect();
		}
		if ($this->settings['elib_settings_jk_remotedb_enabled']){
			$this->Elib_JKDB = new db_driver_mysql;
			$this->Elib_JKDB->obj['sql_user'] = $this->settings['elib_settings_jk_remotedb_login'];
			$this->Elib_JKDB->obj['sql_pass'] = $this->settings['elib_settings_jk_remotedb_password'];
			$this->Elib_JKDB->obj['sql_host'] = $this->settings['elib_settings_jk_remotedb_host'];
			$this->Elib_JKDB->obj['sql_database'] = $this->settings['elib_settings_jk_remotedb_database'];
			$this->Elib_JKDB->obj['force_new_connection']	= 1;
			$this->Elib_JKDB->obj['sql_tbl_prefix'] = '';
			$this->Elib_JKDB->obj['persistent']	 = 1;
			$this->Elib_JKDB->connect();
		}
	}
	
	public function GetName($member){
		$name = $member['name'];
		return ($name);
	}
	
	public function GetMoney($member, $type = 'mc'){
	    $name = $member['name'];
		switch ($type){
		  case 'mc':
		  $res = $this->Elib_MCDB->query("SELECT username,balance FROM iConomy WHERE username='{$name}'");
		   break;
		  case 'jk':
		   $res = $this->Elib_JKDB->query("SELECT username,balance FROM Economy WHERE username='{$name}'");
		   break;
		}
			while( $r = $this->Elib_MCDB->fetch( $res ) ){
			return $r['balance'];
        }
	}
	
	public function UploadSkin($member){

	}
	
	public function UploadCloak($member){
		
	}
	
	public function GetStatus($member){
		$name = $member['name'];
		
	}
	
	public function GetJobLevel($member){
		$name = $member['name'];
	}
	
	public function ConvertSkinToJPG($name){
		
		
	}
	
	public function encrypt($input) {
		$key = $this->settings['elib_settings_mc_auth_keytolauncher'];
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		$input = $this->pkcs5_pad($input, $size); 
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, ''); 
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
		mcrypt_generic_init($td, $key, $iv); 
		$data = mcrypt_generic($td, $input); 
		mcrypt_generic_deinit($td); 
		mcrypt_module_close($td); 
		$data = base64_encode($data); 
		return $data; 
	} 

	private function pkcs5_pad ($text, $blocksize) { 
		$pad = $blocksize - (strlen($text) % $blocksize); 
		return $text . str_repeat(chr($pad), $pad); 
	} 

	public function decrypt($sStr) {
		$sKey = $this->settings['elib_settings_mc_auth_keyfromlauncher'];
		$decrypted= mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$sKey, 
			base64_decode($sStr), 
			MCRYPT_MODE_ECB
		);
		$dec_s = strlen($decrypted); 
		$padding = ord($decrypted[$dec_s-1]); 
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}	
	
	public function xorencode($str, $key) {
		while(strlen($key) < strlen($str)) {
			$key .= $key;
		}
		return $str ^ $key;
	}

	public function strtoint($text) {
		$res = "";
		for ($i = 0; $i < strlen($text); $i++) $res .= ord($text{$i}) . "-";
		$res = substr($res, 0, -1);
		return $res;
	}

	
}