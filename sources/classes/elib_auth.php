<?php

 if(!defined('IN_IPB'))
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}


class public_elib_auth extends class_elib_core{
	protected $badlogin = array('error' => "Bad login",'errorMessage' => "Bad login");
	
	public function joined($profile, $sessionId, $serverId){
		 if (!preg_match("/^[a-zA-Z0-9_-]+$/", $profile) || !preg_match("/^[a-zA-Z0-9:_-]+$/", $sessionId) || !preg_match("/^[a-zA-Z0-9_-]+$/", $serverId)){
			 return false;
		}
		if ($this->settings['elib_settings_mc_remotedb']){
        $res = $this->Elib_MCDB->query("SELECT name, md5 FROM members WHERE md5='{$profile}' AND sessionId='{$sessionId}']");
		$row = $this->Elib_MCDB->fetch($res);
		}else{
			$res = $this->DB->query("SELECT name, md5 FROM members WHERE md5='{$profile}' AND sessionId='{$sessionId}']");
			$row = $this->DB->fetch($res);
		}
		$realmd5 = $row['md5'];
		$realuser = $row['user'];
		$ret = array('id' => $realmd5, 'name' => $realuser);
		
		if ($realmd5 == $profile){
			if ($this->settings['elib_settings_mc_remotedb']){
			    $this->Elib_MCDB->update("members", array('serverId' => $serverId), "sessionId='{$sessionId}' AND md5='{$profile}'");
				return $ret;
			}else{
			    $this->DB->update("members", array('serverId' => $serverId), "sessionId='{$sessionId}' AND md5='{$profile}'");
			    return $ret;
		}
		return false;
	}
	}
	
	public function hasJoined($username, $serverId){
		if (!preg_match("/^[a-zA-Z0-9_-]+$/", $username) || !preg_match("/^[a-zA-Z0-9_-]+$/", $serverId)){
			return false;
		 }
		if ($this->settings['elib_settings_mc_remotedb']){
		    $res = $this->Elib_MCDB->query("SELECT name, md5 FROM ipbmembers WHERE name = '{$username}' and serverId = '{$serverId}'");
		    $row = $this->Elib_MCDB->fetch($res);
		}else{
			$res = $this->DB->query("SELECT name, md5 FROM ipbmembers WHERE name = '{$username}' and serverId = '{$serverId}'");
			$row = $this->DB->fetch($res);
	    }
		$md5 = $row['md5'];
		$realuser = $row['user'];
		 
		if ($username == $realuser){
			$time = time()*1000;
			$base64 ='
			{
				"timestamp":"'.$time.'","profileId":"'.$md5.'","profileName":"'.$realUser.'","textures":
				{
					"SKIN":
					{
						"url":"'.$skinurl.$realUser.'.png"
					},
					"CAPE":
					{
						"url":"'.$capeurl.$realUser.'$.png"
					}
				}
			}';
			 return '
            {
            	"id":"'.$md5.'","name":"'.$realUser.'","properties":
            	[{
            		"name":"textures","value":"'.base64_encode($base64).'","signature":"Cg=="
            	}]
            }';
		}else{
			return false;
		}
	}
	
	public function token() {
        $chars="0123456789abcdef";
        $max=64;
        $size=StrLen($chars)-1;
        $password=null;
        while($max--)
        $password.=$chars[rand(0,$size)];

          return $password;
        }
		
	public function CheckHash($realPass, $postPass, $salt){
		$cryptPass = md5(md5($salt).md5($postPass));
		if (strcmp($realPass, $cryptPass) == 0){
			return true;
		}else{
			return false;
		}
			
	}
	
	public function Auth($login, $pass, $ctoken){
		if (!preg_match("/^[a-zA-Z0-9_-]+$/", $login)){
			return false;
		}
		if ($this->settings['elib_settings_mc_remotedb']){
			$res = $this->Elib_MCDB->query("SELECT name,members_pass_hash,members_pass_salt FROM members WHERE name = '{$login}'");
		    $row = $this->Elib_MCDB->fetch($res);
		}else{
		    $res = $this->DB->query("SELECT name,members_pass_hash,members_pass_salt FROM members WHERE name = '{$login}'");
		    $row = $this->DB->fetch($res);
		}
		$user = $row['name'];
		$password = $row['members_pass_hash'];
		$salt = $row['members_pass_salt'];
		
		if ($this->settings['elib_settings_mc_remotedb']){
			$res = $this->Elib_MCDB->query("SELECT name, md5 FROM members WHERE name = '{$login}'");
		    $row = $this->Elib_MCDB->fetch($res);
		}else{
			$res = $this->DB->query("SELECT name, md5 FROM members WHERE name = '{$login}'");
		    $row = $this->DB->fetch($res);
		}
		if (strcmp($login, $row['name'] == 0)){
			if (strcmp($row['md5'], "") == 0 || strcmp($row['md5']," ") == 0)
			$uuid = $this->uuidConvert($login);
			if ($this->settings['elib_settings_mc_remotedb']){
		    $this->Elib_MCDB->query("UPDATE members SET md5='{$uuid}' WHERE name = '{$login}'");				
			}else{
		    $this->DB->query("UPDATE members SET md5='{$uuid}' WHERE name = '{$login}'");
			}
		}
		
		//CheckPassword
		if ($this->CheckHash($password, $pass, $salt)){
			$accessToken = $this->token();
			$sessionId = $this->token();
			if ($this->settings['elib_settings_mc_remotedb']){
			$this->Elib_MCDB->query("UPDATE members SET sessionId = '{$sessionId}', accessToken = '{$accessToken}' WHERE name = '{$login}'");
			}else{
		    $this->DB->query("UPDATE members SET sessionId = '{$sessionId}', accessToken = '{$accessToken}' WHERE name = '{$login}'");
			}
			return array('token' => $accesstoken, 'session' => $sessionId);
		}else{
			return false;
		}
	}
		
	public function CheckBan($login){
		if ($this->settings['elib_settings_mc_remotedb']){
		    $res = $this->Elib_MCDB->query("SELECT name, banned FROM members WHERE name = '{$login}'");
		    $row = $this->Elib_MCDB->fetch($res);	
		}else{
		    $res = $this->DB->query("SELECT name, banned FROM members WHERE name = '{$login}'");
		    $row = $this->DB->fetch($res);
		}
		if (strcmp($row['banned'], "1") == 0){
			return true;
		}else if(strcmp($row['banned'], "0") == 0){
			return false;
		}else{
			return true;
		}
	}
	
	public function CheckLauncherMD5($launchermd5, $jar){
	  $md5launcherexe = md5_file($this->settings['elib_settings_mc_general_launcherpath']."fix.exe"));
	  $md5launcherjar = md5_file($this->settings['elib_settings_mc_general_launcherpath']."fix.jar"));
		if ($launchermd5 != NULL){
			if ($jar){
			   if($launchermd5 == $this->md5launcherjar){
				   return true;
			    }
			}
			else{
				if($launchermd5 == $this->md5launcherexe){
				   return true;
			    }
			}
			
		}
		return false;
		
	}
	
	public function CheckAssets($client, $assinfolder){
		if ($assetisfolder)
		{ $z = "/"; } else { $z = ".zip"; }
	
		if(!file_exists("clients/assets".$z)||!file_exists("clients/".$client."/bin/")||!file_exists("clients/".$client."/mods/")||!file_exists("clients/".$client."/coremods/")||!file_exists("clients/".$client."/config.zip"))
		return false;
	
	    $md5zip	  = @md5_file("clients/".$client."/config.zip");
        $md5ass	  = @md5_file("clients/assets.zip");
        $sizezip  = @filesize("clients/".$client."/config.zip");
        $sizeass  = @filesize("clients/assets.zip");
		$ret = array('md5zip' => $md5zip, 'md5ass' => $md5ass, 'sizezip' => $sizezip, 'sizeass' => $sizeass);
		return $ret;
	}
	
	private function ListFiles($path){
		$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        $massive = " ";
		    foreach($objects as $name => $object) {
			    $basename = basename($name);
			    $isdir = is_dir($name);
			    if ($basename!="." and $basename!=".." and !is_dir($name)){
			     	$str = str_replace('clients/', "", str_replace($basename, "", $name));
			     	$massive = $massive.$str.$basename.':>'.md5_file($name).':>'.filesize($name).'<:>';
			    }
		    }
		    return $massive;
	}
	
	public function CheckFiles($client, $assinfolder){
		if($assinfolder)
        { $z = "/"; } else { $z = ".zip"; }
		
		if(!file_exists("clients/assets".$z)||!file_exists("clients/".$client."/bin/")||!file_exists("clients/".$client."/mods/")||!file_exists("clients/".$client."/coremods/")||!file_exists("clients/".$client."/config.zip")){
			return false;
		}
        $bin = $this->ListFiles('clients/'.$client.'/bin/');
		$mods = $this->ListFiles('clients/'.$client.'/mods/');
		$coremods = $this->ListFiles('clients/'.$client.'/coremods/');
		$assets = "";
		if ($assinfolder){
		$assets = $this->ListFiles('clients/'.$client.'/assets/');
		}
		return array('bin' => $bin, 'mods' =>$mods, 'coremods' => $coremods, 'assets' => $assets);
	}
	
	//by mssmaks
    private function uuidFromString($string) {
        $val = md5($string, true);
        $byte = array_values(unpack('C16', $val));
 
        $tLo = ($byte[0] << 24) | ($byte[1] << 16) | ($byte[2] << 8) | $byte[3];
        $tMi = ($byte[4] << 8) | $byte[5];
        $tHi = ($byte[6] << 8) | $byte[7];
        $csLo = $byte[9];
        $csHi = $byte[8] & 0x3f | (1 << 7);
 
        if (pack('L', 0x6162797A) == pack('N', 0x6162797A)) {
             $tLo = (($tLo & 0x000000ff) << 24) | (($tLo & 0x0000ff00) << 8) | (($tLo & 0x00ff0000) >> 8) | (($tLo & 0xff000000) >> 24);
             $tMi = (($tMi & 0x00ff) << 8) | (($tMi & 0xff00) >> 8);
             $tHi = (($tHi & 0x00ff) << 8) | (($tHi & 0xff00) >> 8);
        }
 
        $tHi &= 0x0fff;
        $tHi |= (3 << 12);
   
        $uuid = sprintf(
             '%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
             $tLo, $tMi, $tHi, $csHi, $csLo,
             $byte[10], $byte[11], $byte[12], $byte[13], $byte[14], $byte[15]
        );
        return $uuid;
	}
    public function uuidConvert($string)
    {
        $string = $this->uuidFromString("OfflinePlayer:".$string);
        return $string;
    }
	
}
	



