<?php
class TwilioWrapper
{
	
	private static $sid;
	private static $token;
	
	public static function setCredentials($sid='', $token='')
	{		
		if(empty($sid)){
			throw new Exception( t("Invalid account sid") );
		}
		if(empty($token)){
			throw new Exception( t("Invalid auth token") );
		}
		self::$sid = trim($sid); self::$token=trim($token);
	}
	
	public static function sendSMS($params=array())
	{								
		$data = http_build_query($params);		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/'.self::$sid.'/Messages.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, self::$sid . ':' . self::$token);
		
		$resp = curl_exec($ch);		
		if (curl_errno($ch)) {
			throw new Exception( curl_error($ch) );
        }
        curl_close($ch);
        
        if($json = json_decode($resp,true)){        	
        	if(isset($json['sid'])){
        		return $json['sid'];
        	} else {
        		throw new Exception( $json['message'] );
        	}
        } else throw new Exception( t("Invalid response from server") );

	}	
}
/*end class*/