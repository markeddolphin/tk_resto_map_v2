<?php
class CronFunctions extends DbExt
{
	public function getAllCustomer($info='')
	{		
		$stmt="SELECT * FROM
		{{client}}		
		WHERE
		contact_phone!=''
		";
		if ( $res=$this->rst($stmt)){			
			foreach ($res as $val) {				
				$params=array(
				  'broadcast_id'=>$info['broadcast_id'],
				  'client_name'=>$val['first_name']." ".$val['last_name'] ,
				  'contact_phone'=>$val['contact_phone'],
				  'sms_message'=>$info['sms_alert_message'],
				  'date_created'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'client_id'=>$val['client_id'],
				  'merchant_id'=>$info['merchant_id']
				);
				//dump($params);
				if ( !$this->smsDetailsisExist($info['broadcast_id'],$val['client_id'])){
					echo "<p>Insert</p>";
					$this->insertData("{{sms_broadcast_details}}",$params);
				} else echo "<p>Already Exist</p>";			
			}			
		}
	}
	
	public function getAllCustomerByMerchant($info='')
	{		
		$stmt="SELECT a.*
    	FROM
    	{{client}} a
    	WHERE
    	client_id  IN ( select client_id from {{order}} 
    	where client_id=a.client_id and merchant_id=".Yii::app()->db->quoteValue($info['merchant_id'])." )
    	";    			
		if ( $res=$this->rst($stmt)){			
			foreach ($res as $val) {				
				$params=array(
				  'broadcast_id'=>$info['broadcast_id'],
				  'client_name'=>$val['first_name']." ".$val['last_name'] ,
				  'contact_phone'=>$val['contact_phone'],
				  'sms_message'=>$info['sms_alert_message'],
				  'date_created'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'client_id'=>$val['client_id'],
				  'merchant_id'=>$info['merchant_id']
				);
				//dump($params);
				if ( !$this->smsDetailsisExist($info['broadcast_id'],$val['client_id'])){
					echo "<p>Insert</p>";
					$this->insertData("{{sms_broadcast_details}}",$params);
				} else echo "<p>Already Exist</p>";			
			}			
		}
	}	
	
	public function customMobile($info='')
	{		
		$list_mobile_number=isset($info['list_mobile_number'])?explode(",",$info['list_mobile_number']):0;
		if (is_array($list_mobile_number) && count($list_mobile_number)>=1)
		{
			foreach ($list_mobile_number as $mobile) {
				$params=array(
				  'broadcast_id'=>$info['broadcast_id'],
				  'client_name'=>$mobile,
				  'contact_phone'=>$mobile,
				  'sms_message'=>$info['sms_alert_message'],
				  'date_created'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'client_id'=>$mobile,
				  'merchant_id'=>$info['merchant_id']
				);
				//dump($params);
				if ( !$this->smsDetailsisExist($info['broadcast_id'],$mobile)){
					echo "<p>Insert</p>";
					$this->insertData("{{sms_broadcast_details}}",$params);
				} else echo "<p>Already Exist</p>";			
			}
		}
	}		
	
	private function smsDetailsisExist($bid='',$cid='')
	{
		$stmt="SELECT * FROM
		{{sms_broadcast_details}}
		WHERE
		broadcast_id=".FunctionsV3::q($bid)."
		AND
		client_id=".FunctionsV3::q($cid)."
		LIMIT 0,1
		";		
		if ($res=$this->rst($stmt)){	
			return $res;
		}		
		return false;
	}	
	
	public function getPayoutToProcess()
	{
		$date_now=date("Y-m-d");		
		$stmt="SELECT * FROM
		{{withdrawal}}
		WHERE
		status IN ('approved')
		AND date_to_process =".Yii::app()->functions->q($date_now)."		
		ORDER BY withdrawal_id ASC
		LIMIT 0,2
		";		
		if ( $res=$this->rst($stmt)){
			return $res;
		}
		return false;
	}
	
}/* END CLASS*/