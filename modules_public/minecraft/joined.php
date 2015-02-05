<?php
class public_elib_minecraft_joined extends ipsCommand
{
    public function doExecute( ipsRegistry $registry )
    {
		$bad = array('error' => "Bad login",'errorMessage' => "Bad login");
		//$data = $this->request()[''];
		///Override native ipb action system, cuz idk how to get raw post data o.o
	  	if (($_SERVER['REQUEST_METHOD'] == 'POST' ) && (stripos($_SERVER["CONTENT_TYPE"], "application/json") === 0)) {
		$data = json_decode($HTTP_RAW_POST_DATA);
		
		$md5 = $data->selectedProfile; 
		$sessionId = $data->accessToken; 
		$serverId = $data->serverId;
	    $ret = $this->registry->getClass('elib_auth')->joined($md5, $sessionId, $serverId);
		if (!ret){
			exit(json_encode($bad));
		}else{
			exit(json_encode($ret));
		}

		
	}
    }

}