<?php
class SpotHit
{
	static $msg;
	
	public static function sendSMS($to='', $message='', $key='' , $sms_type='', $sms_sender='', $truncate='', $use_curl )
	{
		if (empty($to)){
			self::$msg=t("Invalid mobile number");
		} else {
		    if (substr($to,0,1)!="+"){
		    	$to="+".$to;
		    }
		}
		if (empty($key)){
			self::$msg=t("Invalid api key");
		}
		if (empty($sms_type)){
			$sms_type="premium";
		}
		
		$params = Array(
		    'key' => trim($key),
		    'destinataires' => trim($to),
		    'type' => $sms_type,
		    'message' => $message,
		    //'url'=>"http://bastisapp.com/testcode/"
		);
		if(!empty($sms_sender)){
			$params['expediteur']=$sms_sender;
		}
		if(!empty($truncate)){
			$params['tronque']=$truncate;
		}
		
		///dump($params);
		
		$reponse_json=''; $request='';
		
		if ($use_curl==1){
			$ch = curl_init('http://www.spot-hit.fr/api/envoyer/sms');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params, '', '&'));
			$reponse_json = curl_exec ($ch);
			curl_close ($ch);
		} else {			
			foreach($params as $parameter => $value){
			   $request .= $parameter.'='.urlencode($value).'&';
			}
			$request=substr($request,0,-1);			
			$reponse_json = file_get_contents('http://www.spot-hit.fr/api/envoyer/sms?'.$request);						
		}
		
		//dump($reponse_json);
		if (!empty($reponse_json)){
			$reponse_array = json_decode($reponse_json, true);
			//dump($reponse_array);
			if (is_array($reponse_array) && count($reponse_array)>=1){
				if ($reponse_array['resultat']>0){					
					return $reponse_array['id'];					
				} else self::$msg=self::errorCode($reponse_array['erreurs']);
			} else self::$msg=t("Invalid response");
		} else self::$msg=t("Empty response from server api");
	}
	
	public static function errorCode($code='')
	{
		$error='';
		$error[1]=t('Message type not specified or incorrect ("type" parameter)');
		$error[2]=t('The message is empty');
		$error[3]=t('The message contains more than 160 characters');
		$error[4]=t('No valid recipients are given');
		$error[5]=t('Prohibited number: only consignments in Metropolitan France are authorized for Low Cost SMS');
		$error[6]=t('Invalid recipient number');
		$error[7]=t('Your account does not have a defined formula');
		$error[8]=t('The sender can only contain 11 characters');
		$error[9]=t('The system encountered an error, please contact us.');
		$error[10]=t('You do not have enough text to do this.');
		$error[11]=t('The sending of the messages is deactivated for the demonstration.');
		$error[12]=t('Your account has been suspended. Contact us for more information');
		$error[13]=t('Your configured send limit is reached. Contact us for more information.');
		$error[14]=t('Your configured send limit is reached. Contact us for more information.');
		$error[15]=t('Your configured send limit is reached. Contact us for more information.');
		$error[16]=t('The "smslongnbr" parameter is not consistent with the size of the message being sent.');
		$error[17]=t('The sender is not allowed.');
		$error[18]=t('The subject is too short.');
		$error[19]=t('The reply e-mail is invalid.');
		$error[20]=t('The sender name is too short.');
		$error[21]=t('Token invalid. Contact us for more information.');
		$error[22]=t('Unauthorized message length. Contact us for more information.');
		$error[30]=t('API key not recognized.');
		
		if (array_key_exists($code,$error)){
			return $error[$code];
		}
		return t("ERROR code")." :".$code;
	}
		
}/* end class*/