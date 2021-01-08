<?php
class Clickatell
{
	public $host='http://api.clickatell.com/http/sendmsg?';	
	public $user;
	public $password;
	public $api_id;
	public $is_curl=false;
	public $to;
	public $message;	
	public $unicode=false;
	public $sender='';
	
	public function sendSMS()
	{
		if (empty($this->user)){
			throw new Exception(t("User is empty"));
		}
		
		if (empty($this->password)){
			throw new Exception(t("Password is empty"));
		}
		
		if (empty($this->api_id)){
			throw new Exception(t("Api is empty"));
		}
		
		if (empty($this->to)){
			throw new Exception(t("To is required"));
		}
		
		if (empty($this->message)){
			throw new Exception(t("Message is required"));
		}

		$params="user={user}&password={password}&api_id={api_id}&to={to}&text={text}";
		if ( $this->unicode==true){
			$params.="&unicode=1";
		}		
		if (!empty($this->sender)){
			$params.="&from=".urlencode($this->sender);
		}
		
		if(!empty($this->to)){
			$this->to=str_replace("+",'',$this->to);
			$this->to="+".$this->to;
		}		
					
		$params=$this->smarty('user',$this->user,$params);
		$params=$this->smarty('password',$this->password,$params);
		$params=$this->smarty('api_id',$this->api_id,$params);
		$params=$this->smarty('to',urlencode($this->to),$params);
		if ( $this->unicode==true){			
			$params=$this->smarty('text',$this->ToUnicode($this->message),$params);
		} else $params=$this->smarty('text',urlencode($this->message),$params);		
				
		if ($this->is_curl==FALSE){			
			$resp=file_get_contents($this->host.$params);
						
			if(!empty($resp)){
				if (!preg_match("/ERR/i", $resp)) {
					return $resp;
				} else throw new Exception($resp);
			} else {
				throw new Exception(t("empty response from api"));
			}
		} else {			
			$resp=$this->Curl($this->host,$params);
			if(!empty($resp)){
				if (!preg_match("/ERR/i", $resp)) {
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
	
	public function ToUnicode($message) {		
	     $arr = unpack('H*hex', iconv('UTF-8', 'UCS-2BE', $message));	     
         return strtoupper($arr['hex']);        
	}
			
} /*END CLASS*/