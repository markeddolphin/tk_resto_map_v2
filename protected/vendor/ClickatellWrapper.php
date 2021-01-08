<?php
class ClickatellWrapper
{
	
	public static function sendSMS($api_key='', $to='', $content='',$use_curl=false,$use_unicode=false)
	{
		$api_url = 'https://platform.clickatell.com/messages/http/send';
		
		if (empty($api_key)){
		    throw new Exception( "invalid api key" );
		}
		if (empty($to)){
		    throw new Exception( "invalid to parameters" );
		}
		if (empty($content)){
		    throw new Exception( "content is required" );
		}
		
		if($use_unicode){			
			$content = self::ToUnicode($content);			
		}
		
		$params=array(
		  'apiKey'=>$api_key,
		  'to'=>str_replace("+","",$to),
		  'content'=>$content
		);
		
		$resp=''; $data='';
		
		$url = "$api_url?".http_build_query($params);
		
		if(!$use_curl){			
			$resp = @file_get_contents($url);
		} else {			
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST,false);			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$resp=curl_exec ($ch);		
			$error = curl_error($ch);
			curl_close($ch);			
			if ($error !== '') {				
				throw new Exception( $error );				
			}
		}
		
		/*dump("<H2>Response</h2>");
		dump($resp);*/
				
		if(!empty($resp)){
			$json=json_decode($resp,true);						
			//dump($json);
			if(is_array($json) && count( (array)$json)>=1){
				
				if($json['errorCode']>=1){
					throw new Exception( $json['errorDescription'] );
				} 
				
				if(isset($json['messages'])){
					foreach ($json['messages'] as $val) {						
						if($val['errorCode']>=1){
							throw new Exception( $val['errorDescription'] );
						}		
						$data.= json_encode($val);
					}					
					return $data;
				} else {
					if(isset($json['error'])){
						throw new Exception( $json['error'] );
					} else throw new Exception( "an error has occured" );
				}
			} else throw new Exception( "invalid response from api ".$json );
		} else throw new Exception( "empty response from api" );
	}
		
	public static function ToUnicode($message) {		
	     $arr = unpack('H*hex', iconv('UTF-8', 'UCS-2BE', $message));	     
         return strtoupper($arr['hex']);        
	}
	
} /*end class*/