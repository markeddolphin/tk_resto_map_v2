<?php
class Msg91
{	
	static $msg;
	
	public static function sendSMS( $auth_key='', $mobile_number='', $senderid='', $message='', $unicode='', 
	$route='default' )
	{
		$postData = array(
	        'authkey' => $auth_key,
	        'mobiles' => $mobile_number,
	        'message' => urlencode($message),
	        'sender'  => $senderid,
	        'route'   => $route,
	        'response'=>"json"
        );
        
        if($unicode==1){
        	$postData['unicode']=1;
        }
        
        //dump($postData);
        
        $mobile_number=str_replace("+","",$mobile_number);
                
        //$url="https://control.msg91.com/api/sendhttp.php";
        $url="http://api.msg91.com/api/sendhttp.php";
        
        $ch = curl_init();
	    curl_setopt_array($ch, array(
	        CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_POST => true,
	        CURLOPT_POSTFIELDS => $postData
	        //,CURLOPT_FOLLOWLOCATION => true
	    ));
	    
	    //Ignore SSL certificate verification
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       $output = curl_exec($ch);
       
       if(curl_errno($ch)){
	      self::$msg =  'error:' . curl_error($ch);
	      return false;
	   }        		
	   curl_close($ch);	   
	   if(!empty($output)){
	   	   $res=json_decode($output,true);	   	   
	   	   if (isset($res['type'])){
	   	   	   if ( $res['type']=="success"){
	   	   	   	   return $res['message'];
	   	   	   } else self::$msg=$res['message'];
	   	   } else self::$msg="Undefine error";
	   } else self::$msg="Empty response from api";
	}
	
	
} /*end class*/