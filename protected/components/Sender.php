<?php
class Sender
{
	var $host;
	var $port;
	var $strUsername;
	var $strPassword;
	var $strSender;
	var $strMessage;
	var $strMobile;
	/*
	0 - plain text
	1 - flash
	2 - unicode message
	6 - Unicode flash
	*/
	var $strMessageType;
	
	/*
	whether you require delivery report or not
	0 - not required
	1 - required
	*/
	
	var $strDlr;
	
	private function sms_unicode($message)
	{
		$hex1='';
		if(function_exists('iconv')){
			$latin = @iconv('UTF-8','ISO-8859-1',$message);
			if(strcmp($latin,$message)){
				$arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE', $message));
				$hex1 = strtoupper($arr['hx']);
			}
			if($hex==''){
				$hex2='';
				$hex='';
				for($i=0;$i<strlen($message);$i++){
					$hex=dechex(ord($message[$i]));
					$add = 2-len;
					if($len<4){
						for($j=0;$j<$add;$j++){
							$hex="0".$hex;
						}
					}
					$hex2.=$hex;
				}
				return $hex2;
			}
			else
			{
				return $hex1;
			}
		}
		else
		{
			print 'iconv Function Not exists !';
		}
	}
	
	public function Sender($host,$port,$username,$password, $sender,$message, $mobile,$msgtype,$dlr){
		$this->host = $host;
		$this->port = $port;
		$this->strUserName = $username;
		$this->strPassword = $password;
		$this->strSender = $sender;
		$this->strMessage = $message;
		$this->strMobile = $mobile;
		$this->strMessageType = $msgtype;
		$this->strDlr = $dlr;
	}
	
	public function Submit()
	{
		if($this->strMessageType=="2" || $this->strMessageType=="6")
		{
			$this->strMessage=$this->sms_unicode($this->strMessage);
			try{
				$live_url="http://".$this->host.":".$this->port."/bulksms/bulksms?username=".$this->strUserName."&password=".$this->strPassword."&type=".$this->strMessageType."&dlr=".$this->strDlr."&destination=".$this->strMobile."&source=".$this->strSender."&message=".$this->strMessage."";
//				print $live_url;exit();
				$parse_url=file($live_url);
				return $parse_url[0];
			}catch(Exception $e){
				return 'Message:'.$e->getMessage();
			}
		}
		else
		{
			$this->strMessage=urlencode($this->strMessage);
			try
			{
				$live_url="http://".$this->host.":".$this->port."/bulksms/bulksms?username=".$this->strUserName."&password=".$this->strPassword."&type=".$this->strMessageType."&dlr=".$this->strDlr."&destination=".$this->strMobile."&source=".$this->strSender."&message=".$this->strMessage."";
//				print $live_url;exit();
				$parse_url=file($live_url);
				return $parse_url[0];
			}catch(Exception $e){
				return 'Message:'.$e->getMessage();
			}
		}
	}
}

?>