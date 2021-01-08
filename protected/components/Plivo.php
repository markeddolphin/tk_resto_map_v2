<?php
class Plivo
{	
	public $host='https://api.plivo.com/v1/Account/{auth_id}/Message/';
	public $auth_id;
	public $auth_token;
	public $sender;
	public $to;
	public $message;

	public function sendSMS()
	{
		
		if(empty($this->auth_id)){
			throw new Exception(t("auth id is empty"));			
		}
		
		if(empty($this->auth_token)){
			throw new Exception(t("auth token is empty"));
		}
		
		if(empty($this->sender)){
			throw new Exception(t("sender number is empty"));
		}
		
		if(empty($this->to)){
			throw new Exception(t("destination is empty"));
		}
		
		if(empty($this->message)){
			throw new Exception(t("message is empty"));
		}
		
		$host=smarty("auth_id",$this->auth_id,$this->host);		
		
		$post=array(
		  'src'=>$this->sender,
		  'dst'=>$this->to,
		  'text'=>$this->message
		);		
		if ( $res=$this->Curl($host,$post)){			
			if(!empty($res)){
				$resp=json_decode($res,true);
				if(is_array($resp) && count($resp)>=1){
					if (array_key_exists('error',(array)$resp)){
						throw new Exception($resp['error']);
					} else {
						return $resp['message'];
					}
				} else throw new Exception($res);
			} else throw new Exception(t("empty response from api"));
		} else {
			throw new Exception(t("Invalid response from api"));
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
		 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post,JSON_FORCE_OBJECT));		 
		 curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
         curl_setopt($ch, CURLOPT_USERPWD, "$this->auth_id:$this->auth_token");
		 $resutl=curl_exec ($ch);		
		 		 		 		 
		 if ($error_no==0) {
		 	 return $resutl;
		 } else return false;			 
		 curl_close ($ch);		 				 		 		 		 		 		
	}
	
} /*end class*/