<?php



class public_elib_payment_interkassa extends ipsCommand
{
    public function doExecute( ipsRegistry $registry )
    {
	  $data = $registry->request()['reply'];
	  if (strcmp($data, "success") == 0 || strcmp($data, "fail") == 0){
		 die("<META HTTP-EQUIV='REFRESH' CONTENT='0;URL=http://jknet.hopto.org/");
	  }else{
		  $data = $registry->request();
		  $amount = (string)$data['ik_am'];
		  if( $this->registry->getClass('elib_bill')->IK_CheckSign($registry->request())){
			  $this->registry->getClass('elib_bill')->Apply($amount, $data['ik_pm_no']);
		  }
	  }
	  
    }
}