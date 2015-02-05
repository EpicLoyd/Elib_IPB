<?php
class public_elib_minecraft_hasjoined extends ipsCommand
{
    public function doExecute( ipsRegistry $registry )
    {
  	   $user     = $this->request()['username'];
       $serverid = $this->request()['serverId'];
	   
	   if(!$this->registry->getClass('elib_auth')->hasJoined($user, $serverid))
		   exit(json_encode(array('error' => "Bad login",'errorMessage' => "Bad login"))
    }

}