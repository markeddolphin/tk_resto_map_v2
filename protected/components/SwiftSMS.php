<?php
class SwiftSMS
{
	public $host='http://smsgateway.ca/sendsms.aspx?';	
	public $account_key;	
	public $is_curl=false;
	public $to;
	public $message;		
	public $sender='';
	public $sms_type='normal';
	public $priority='dnd';
	
	public function sendSMS()
	{	
		
		if (empty($this->account_key)){
			throw new Exception(t("account key is empty"));
		}
		
		if (empty($this->to)){
			throw new Exception(t("To is required"));
		}
		
		if (empty($this->message)){
			throw new Exception(t("Message is required"));
		}

		$params="CellNumber={phone}&MessageBody={text}&AccountKey={accountkey}";		
			
		$params=$this->smarty('accountkey',$this->account_key,$params);
		$params=$this->smarty('sender',urlencode($this->sender),$params);
		$params=$this->smarty('phone',urlencode($this->to),$params);		
		$params=$this->smarty('text',urlencode($this->message),$params);				
				
		/*echo $this->host;
		echo $params;*/
		//die();
						
		if ($this->is_curl==FALSE){						
			$resp=file_get_contents($this->host.$params);															
			$resp=trim($resp);		
			/*echo "<h1>response</h1>";
			dump($resp);*/	
			if(!empty($resp)){				
				if (preg_match("/successfully/i", $resp)) {	
					return $resp;
				} else throw new Exception($resp);
			} else {
				throw new Exception(t("empty response from api"));
			}
		} else {			
			$resp=$this->Curl($this->host,$params);			
			$resp=trim($resp);
			/*echo "<h1>response</h1>";
			dump($resp);*/
			if(!empty($resp)){				
				if (preg_match("/successfully/i", $resp)) {		
					return $resp;
				} else throw new Exception($resp);
			} else {
				throw new Exception(t("empty response from api"));
			}
		}
	}			
	
	public function smarty($search='',$value='',$subject='')
    {	
	   return str_replace("{".$search."}",$value,$subject);
    }
    
    public function Curl($uri="",$post="")
	{
		 $error_no='';
		 $ch = curl_init($uri);
		 curl_setopt($ch, CURLOPT_POST, 1);		 
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $post);		 
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 $resutl=curl_exec ($ch);		
		 		 		 		 
		 if ($error_no==0) {
		 	 return $resutl;
		 } else return false;			 
		 curl_close ($ch);		 				 		 		 		 		 		
	}
	
} /*END CLASS*/