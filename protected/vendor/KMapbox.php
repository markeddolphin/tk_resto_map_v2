<?php
class KMapbox
{
	static $token;
	static $msg;
	static $unit;
	
	public static function setToken($token='')
	{
		self::$token=$token;
		self::$unit='';
	}
	
	public static function matrix($origin='', $destination='' , $unit='')
	{ 				
		$qry_str="?&access_token=".self::$token;		
		$api_url = "https://api.mapbox.com/optimized-trips/v1/mapbox/driving/$origin;$destination";		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url . $qry_str);  		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $content = trim(curl_exec($ch));
        
        if(curl_errno($ch)){
	      self::$msg =  'error:' . curl_error($ch);
	      return false;
	    }        		 	   
        curl_close($ch); 
        if(isset($_GET['debug'])){
           dump($content); 
        }        
        if(!empty($content)){
        	$json = json_decode($content,true);
        	//dump($json);
        	if(is_array($json) && count($json)>=1){        		
        		if(isset($json['code'])){
	        		if(strtolower($json['code'])=="ok"){
	        			if(isset($json['trips'])){
	        				$trips = $json['trips'][0];
	        				$duration = $trips['duration'];
	        				$distance = $trips['distance'];
	        				
	        				if(isset($trips['legs'])){
	        					if(isset($trips['legs'][0])){	        						
	        						$distance = $trips['legs'][0]['distance'];
	        						$duration = $trips['legs'][0]['duration'];
	        					}
	        				}
	        				
	        				/*dump("unit : $unit");
	        				dump("duration :$duration");
	        				dump("distance :$distance");*/
	        				//die();
	        				
	        				$raw_value=0;
	        				
	        				switch ($unit) {
	        					case "M":
	        						$raw_value = self::metersToMiles($distance);
	        						$raw_value = number_format($raw_value,1,'.','');
	        						if($raw_value<1){
	        							self::$unit = "ft";
	        							$raw_value = self::metersToFeet($raw_value);
	        							$raw_value = number_format($raw_value,1,'.','');
	        						} 
	        						break;
	        				
	        					case "K":
	        						$raw_value = self::metersToMiles($distance);
	        						$raw_value = self::milesToKilometers($raw_value);
	        						$raw_value = number_format($raw_value,1,'.','');
	        						if($raw_value<1){
	        							self::$unit = "meter";	        							
	        						}
	        						break;
	        						
	        					default:
	        						break;
	        				}
	        				
	        				//dump("raw_value: $raw_value");
	        				return array(
	        				  'raw_value'=>$raw_value,
	        				  'unit'=>self::$unit
	        				);
	        				
	        			} else self::$$msg = "trips not found";
	        		} else self::$msg = $json;
        		} else {
        			if(isset($json['message'])){
        				self::$msg=$json['message'];
        			} else self::$$msg = "Undefined error message";
        		}
        	} else self::$$msg = "Response is not an array";
        } else self::$msg = "Invalid response";
        return false;
	}
	
	public static function secondsToTime($seconds_time)
	{
	    if ($seconds_time < 24 * 60 * 60) {
	        return gmdate('H:i:s', $seconds_time);
	    } else {
	        $hours = floor($seconds_time / 3600);
	        $minutes = floor(($seconds_time - $hours * 3600) / 60);
	        $seconds = floor($seconds_time - ($hours * 3600) - ($minutes * 60));
	        return "$hours:$minutes:$seconds";
	    }
	}
	
	public static function metersToMiles($meters){
       return $meters * 0.000621371;
    }
    
    public static function milesToKilometers($miles){
       return $miles * 1.60934;
    }
    
    public static function metersToFeet($meters) {
      return floatval($meters) * 3.2808399;
    }
	
} /*end class*/