<?php
class GoogleCaptcha
{		
	public static $capctha_url='https://www.google.com/recaptcha/api/siteverify';
	public static $message;
	
	public static function checkCredentials()
	{
		$captcha_site_key=getOptionA('captcha_site_key');
		$captcha_secret=getOptionA('captcha_secret');
		$captcha_lang=getOptionA('captcha_lang');
		if (!empty($captcha_site_key) && !empty($captcha_secret)){
			return true;
		}
		return false;
	}
	
	public static function displayCaptcha()
	{
	   $site_key=getOptionA('captcha_site_key');
	   $lang=getOptionA('captcha_lang');
	   if (empty($lang)){
	   	  $lang="en";
	   }
	   if (!self::checkCredentials()){
	   	  return false;
	   }
	   $id=Yii::app()->functions->generateRandomKey();	   
	   ?>
	   <div class="uk-form-row">     
	     <div class="g-recaptcha" data-sitekey="<?php echo $site_key ?>"></div>	     
	     <script type="text/javascript"
	            src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang?>">
	     </script>
	   </div>
	   <?php
	}
	
	public static function validateCaptcha()
	{  		
	   $secret_key=getOptionA('captcha_secret');
	   if (empty($secret_key)){
	   	   return false;
	   }	 
	   	   
	   $ch = curl_init(); 
	   curl_setopt($ch,CURLOPT_URL,self::$capctha_url); 
	     
	   curl_setopt($ch,CURLOPT_POSTFIELDS,"secret=$secret_key&response=".$_REQUEST['g-recaptcha-response']); 
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
	   $response = curl_exec($ch); 	   
	   curl_close($ch); 
	   //dump($response);
	   $response=!empty($response)?json_decode($response,true):false;
	   //dump($response);
	   if ( isset($response['error-codes'])){
	   	   if (is_array($response['error-codes']) && count($response['error-codes'])>=1){
	   	   	  foreach ($response['error-codes'] as $val) {
	   	   	  	 self::$message.="$val";
	   	   	  }
	   	   	  return false;
	   	   }
	   }
	   return true;
	}
	
}/** end class*/