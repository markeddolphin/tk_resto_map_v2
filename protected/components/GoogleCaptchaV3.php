<?php
class GoogleCaptchaV3
{
	
	public static function init()
	{
		$captcha_site_key = getOptionA('captcha_site_key');
		if(!empty($captcha_site_key)){
			$cs = Yii::app()->getClientScript();
			$cs->registerScriptFile("https://www.google.com/recaptcha/api.js?render=$captcha_site_key",CClientScript::POS_HEAD); 
			$cs->registerScriptFile(Yii::app()->baseUrl."/assets/js/recaptchav3.js",CClientScript::POS_END);            
			$cs->registerScript(
			  'captcha_site_key',
			  "var captcha_site_key ='".$captcha_site_key."' ;",			
			  CClientScript::POS_HEAD
			);			
		}
	}
	
	public static function validateToken($token='')
	{		
		$captcha_secret = getOptionA('captcha_secret');
		if(empty($captcha_secret)){
			throw new Exception( t("captcha secret is invalid") );
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $captcha_secret, 'response' => $token)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		
		if (curl_errno($ch)) {
           $err =  curl_error($ch);
           throw new Exception( Yii::t("default","An error has occured. [error]",array('[error]'=>$err) ) );
        }
		
		curl_close($ch);
		$resp = json_decode($response, true);		
		if(is_array($resp) && count($resp)>=1){
			if($resp["success"] == '1' && $resp["action"] == "login" && $resp["score"] >= 0.5) {
				return $resp;
			} else {
				$error='';
				foreach ($resp['error-codes'] as $val) {
					$error.=$val;
				}
				throw new Exception( Yii::t("default","Captcha validation error : [error]",array(
				  '[error]'=>$error
				)) );
			}
		}
		throw new Exception( t("Failed validating captcha") );
	}
	
} /*end class*/