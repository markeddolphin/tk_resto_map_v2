<?php
class SolutionsinfiniSMS
{
	public $host='http://api.alerts.solutionsinfini.com/v3/?';	
	public $api_key;	
	public $is_curl=false;
	public $to;
	public $message;		
	public $sender='';
	public $unicode='';
	
	public function sendSMS()
	{	
		
		if (empty($this->api_key)){
			throw new Exception(t("api key is empty"));
		}
		
		if (empty($this->sender)){
			throw new Exception(t("sender is empty"));
		}
		
		if (empty($this->to)){
			throw new Exception(t("To is required"));
		}
		
		if (empty($this->message)){
			throw new Exception(t("Message is required"));
		}

		$params="method=sms&api_key={api_key}&to={phone}&sender={sender}&message={text}&format=JSON";
		if($this->unicode){
			$params.="&unicode=1";
		}
			
		$params=$this->smarty('api_key',$this->api_key,$params);
		$params=$this->smarty('sender',urlencode($this->sender),$params);
		$params=$this->smarty('phone',urlencode($this->to),$params);		
		$params=$this->smarty('text',urlencode($this->message),$params);				
				
		/*echo $this->host;
		echo $params;
		die();*/
						
		if ($this->is_curl==FALSE){						
			$resp=file_get_contents($this->host.$params);															
			$resp=trim($resp);		
			if(!empty($resp)){				
			   $resp_json=json_decode($resp,true);
			   if(is_array($resp_json) && count($resp_json)>=1){
			   	  if($resp_json['status']=="OK"){
			   	  	 return $resp_json['status'];
			   	  } else throw new Exception( $resp_json['message'] );
			   } else throw new Exception(t("Invalid response from api"));
			} else {
				throw new Exception(t("empty response from api"));
			}
		} else {			
			$resp=$this->Curl($this->host,$params);			
			$resp=trim($resp);
			if(!empty($resp)){				
			   $resp_json=json_decode($resp,true);
			   if(is_array($resp_json) && count($resp_json)>=1){
			   	  if($resp_json['status']=="OK"){
			   	  	 return $resp_json['status'];
			   	  } else throw new Exception( $resp_json['message'] );
			   } else throw new Exception(t("Invalid response from api"));
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