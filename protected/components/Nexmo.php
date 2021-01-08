<?php
class Nexmo
{
	public $host='https://rest.nexmo.com/sms/json?';	
	public $sender;
	public $key;
	public $secret;
	public $is_curl=false;
	public $to;
	public $message;	
	public $unicode=false;
	
	public function sendSMS()
	{
		if (empty($this->sender)){
			throw new Exception(t("Sender is empty"));
		}
		
		if (empty($this->key)){
			throw new Exception(t("Key is empty"));
		}
		
		if (empty($this->secret)){
			throw new Exception(t("Secret is empty"));
		}
		
		if (empty($this->to)){
			throw new Exception(t("To is required"));
		}
		
		if (empty($this->message)){
			throw new Exception(t("Message is required"));
		}

		$params="api_key={api_key}&api_secret={api_secret}&from={from}&to={to}&text={text}";
		if ( $this->unicode==true){
			$params.="&type=unicode";
		}
		
		/*$_first = substr($this->to,0,1);		
		if($_first==0){
			$this->to = substr($this->to,1,strlen($this->to));
		}*/		
			
		if (!@preg_match("/+/i", $this->to)) {
			$this->to="+".$this->to;
		}		
		
		$params=$this->smarty('api_key',$this->key,$params);
		$params=$this->smarty('api_secret',$this->secret,$params);
		$params=$this->smarty('from',$this->sender,$params);
		$params=$this->smarty('to',urlencode($this->to),$params);
		$params=$this->smarty('text',urlencode($this->message),$params);
		
		if ($this->is_curl==FALSE){			
			$resp=file_get_contents($this->host.$params);
			if(!empty($resp)){
				$resp_array=json_decode($resp,true);				
				if (is_array($resp_array) && count($resp_array)>=1){
					if ($resp_array['messages'][0]['status']<=0){						
						return $resp_array['messages'][0]['message-id'];
					} else {
						throw new Exception($resp_array['messages'][0]['error-text']);
					}
				} else throw new Exception(t("invalid response from api"));
			} else {
				throw new Exception(t("empty response from api"));
			}
		} else {			
			$resp=$this->Curl($this->host,$params);		
			if(!empty($resp)){
				$resp_array=json_decode($resp,true);				
				if (is_array($resp_array) && count($resp_array)>=1){
					if ($resp_array['messages'][0]['status']<=0){						
						return $resp_array['messages'][0]['message-id'];
					} else {
						throw new Exception($resp_array['messages'][0]['error-text']);
					}
				} else throw new Exception(t("invalid response from api"));
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