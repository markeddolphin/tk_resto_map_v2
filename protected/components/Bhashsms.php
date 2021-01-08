<?php
class Bhashsms
{
	public $host='http://bhashsms.com/api/sendmsg.php?';	
	public $user;
	public $password;	
	public $is_curl=false;
	public $to;
	public $message;		
	public $sender='';
	public $sms_type='normal';
	public $priority='dnd';
	
	public function sendSMS()
	{	
		if (empty($this->user)){
			throw new Exception(t("User is empty"));
		}
		
		if (empty($this->password)){
			throw new Exception(t("Password is empty"));
		}
		
		if (empty($this->sender)){
			throw new Exception(t("Sender is empty"));
		}
		
		if (empty($this->to)){
			throw new Exception(t("To is required"));
		}
		
		if (empty($this->message)){
			throw new Exception(t("Message is required"));
		}

		$params="user={user}&pass={password}&sender={sender}&phone={phone}&text={text}&priority={priority}&stype={stype}";		
							
		if (!empty($this->to)){
			$this->to=str_replace("+91","",$this->to);
		}
		
		$params=$this->smarty('user',$this->user,$params);
		$params=$this->smarty('password',$this->password,$params);
		$params=$this->smarty('sender',urlencode($this->sender),$params);
		$params=$this->smarty('phone',urlencode($this->to),$params);		
		$params=$this->smarty('text',urlencode($this->message),$params);		
		$params=$this->smarty('priority',$this->priority,$params);		
		$params=$this->smarty('stype',$this->sms_type,$params);		
				
		/*echo $this->host;
		echo $params;*/
		//die();
						
		if ($this->is_curl==FALSE){						
			$resp=file_get_contents($this->host.$params);															
			$resp=trim($resp);			
			if(!empty($resp)){				
				if (strlen($resp)<=11){			
					return $resp;
				} else throw new Exception($resp);
			} else {
				throw new Exception(t("empty response from api"));
			}
		} else {			
			$resp=$this->Curl($this->host,$params);			
			$resp=trim($resp);
			if(!empty($resp)){				
				if (strlen($resp)<=11){			
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