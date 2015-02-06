<?php

if(!defined('IN_IPB'))
{
	print "<h1>Incorrect access</h1>You cannot access this file directly. If you have recently upgraded, make sure you upgraded all the relevant files.";
	exit();
}

class public_elib_bill extends class_elib_core{
	protected $ik = array();
	protected $ik = array();
	
	public function Init(){
		
		
	}
	
	private function IK_CheckSign($data){
		///Clean data array
		foreach ($data as $key => $value)
		{
			if (!preg_match('/ik_/', $key)) continue;
			$data[$key] = $value;
		}
		///
		$ikSign = $data['ik_sign'];
		unset($data['ik_sign']);
		$key = ($data['ik_pw_via'] == 'test_interkassa_test_xts') ? $this->settings['elib_settings_bill_ik_keytest'] : $this->settings['elib_settings_bill_ik_key'];
		ksort ($data, SORT_STRING);
		array_push($data, $key);
		$signStr = implode(':', $data);
		$sign = base64_encode(md5($signStr, true));
		return ($sign == $ikSign) ? true : false;
		
	}
	
	private function UP_CheckSign($data){
		ksort($data);
		$exp = explode("-", $this->settings['elib_settings_bill_up_id']);
		$Sign = $data['sign'];
		unset($data['sign']);
		$data['projectId'] = $exp[0];
		if (md5(join(null, $data).$this->settings['elib_settings_bill_up_key']) == $Sign){
		  return true;
		}else{
		  return false;
		}
		
	}
	
	public function IK_Check($data){
		
		
	}
    
    public function UP_Check($data){
	   if ($this->UP_CheckSign($data)){
		   $ret = array(
				"jsonrpc" => "2.0",
				"result" => array("message" => "Успешно!"),
				'id' => $this->settings['elib_settings_bill_up_id']
			)
			return json_encode($ret);
	   }
	   else{
		   $ret = array(
				"jsonrpc" => "2.0",
				"error" => array("code" => -32000, "message" => "Ошибка!",
				'id' => $this->settings['elib_settings_bill_up_id']
			),
		   
	   }
    }
	
	public function IK_Request($amount, $user){
		if ($amount > $this->settings['elib_settings_bill_maxpay'] || $amount <= 0 )return false;
		$desc = $this->settings['elib_settings_bill_desc']." - User: ".$user." Native Amount: ".$amount;
		
		return "https://sci.interkassa.com/?ik_co_id=".$this->settings['elib_settings_bill_ik_id']."&ik_pm_no=".$user."&ik_am=".$amount."&ik_cur=RUB&ik_desc=".$desc;
	}
	
	public function UP_Request($amount, $user){
		if ($amount > $this->settings['elib_settings_bill_maxpay'] || $amount <= 0 )return false;
		$desc = $this->settings['elib_settings_bill_desc']." - User: ".$user." Native Amount: ".$amount;
		
		return "https://unitpay.ru/pay/".$this->settings['elib_settings_bill_up_id']."?sum=".$amount."&account=".$user."&desc=".$desc;
		
	}
	
	public function Pay($amount, $login){
		
		
	}
	
	
}