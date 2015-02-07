<?php



class public_elib_minecraft_auth extends ipsCommand
{
    public function doExecute( ipsRegistry $registry )
    {
	  $launcherversion = $this->settings['elib_settings_mc_launcher_ver'];
	  $assfolder = $this->settings['elib_settings_mc_launcher_assets'];
      $data = $registry->request()['action'];
	  $data = str_replace(" ", "+", $data);
	  $dec = $this->registry->getClass('elib_core')->decrypt($data);
	  list($action, $clientname, $login, $pass, $launchermd5, $ctoken) = explode(':', $dec);
	  $login = mysql_real_escape_string($login);
	  if ($this->settings['elib_settings_mc_launcher_md5']){
	  $isuptodate = $this->registry->getClass('elib_auth')->CheckLauncherMD5($launchermd5, true);
	  if (!$isuptodate){
		  exit($this->registry->getClass('elib_core')->encrypt("badlauncher<$>_$masterversion"));
	  }
	  }
	  
	  $isauth = $this->registry->getClass('elib_auth')->Auth($login, $pass, $ctoken);
	  if (!$isauth){
		  exit($this->registry->getClass('elib_core')->encrypt("errorLogin<$>"));
	  }else{
		   $isbanned = $this->registry->getClass('elib_auth')->CheckBan($login);
	       if ($isbanned){
		       exit($this->registry->getClass('elib_core')->encrypt("Вечный бан"));
	       }
		  $accesstoken = $isauth['token'];
		  $sessionid = $isauth['session'];
		  $realUser = $isauth['user'];
		  $assets = $this->registry->getClass('elib_auth')->CheckAssets($clientname, $assfolder);
		  if (!$assets){
			   die($this->registry->getClass('elib_core')->encrypt("client<$> ".$clientname));
		  }
		  $md5zip = $assets['md5zip'];
		  $md5ass = $assets['md5ass'];
		  $sizezip = $assets['sizezip'];
		  $sizeass = $assets['sizeass'];
		  $client = $this->registry->getClass('elib_auth')->CheckFiles($clientname, $assfolder);
		  if (!$client){
			  die($this->registry->getClass('elib_core')->encrypt("client<$> ".$clientname));
		  }
		  $answer = "$launcherversion<:>$login<:>".md5zip."<>".$sizezip."<:>".$md5ass."<>".$sizeass."<br>".$realUser."<:>".$this->registry->getClass('elib_core')->strtoint($this->registry->getClass('elib_core')->xorencode($sessionid, $this->settings['elib_settings_mc_auth_protectkey']))."<br>".$accesstoken.'<br>';
		  if ($assfolder){
			  echo $this->registry->getClass('elib_core')->encrypt($answer.str_replace("\\", "/",$client['bin'].$client['mods'].$client['coremods'].$client['assets']).'<::>assets/indexes<:b:>assets/objects<:b:>assets/virtual<:b:>'.$client.'/bin<:b:>'.$client.'/mods<:b:>'.$client.'/coremods<:b:>');
		  }else{
			  echo $this->registry->getClass('elib_core')->encrypt($answer.str_replace("\\", "/",$client['bin'].$client['mods'].$client['coremods']).'<::>'.$client.'/bin<:b:>'.$client.'/mods<:b:>'.$client.'/coremods<:b:>');
		  }
	  }
    }
}