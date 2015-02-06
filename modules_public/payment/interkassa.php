<?php



class public_elib_payment_interkassa extends ipsCommand
{
    public function doExecute( ipsRegistry $registry )
    {
	  $data = $this->request()['reply'];
	  if (strcmp($data, "success") == 0 || strcmp($data, "fail") == 0){
		 die("<META HTTP-EQUIV='REFRESH' CONTENT='0;URL=http://casioo.ru/'");
	  }else{
		  if( $this->registry->getClass('elib_bill')->IK_CheckSign($this->request())){
			  $this->registry->getClass('elib_bill')->Pay($data['ik_am'], $data['ik_pm_no']);
		  }
	  }
	  
    }
}