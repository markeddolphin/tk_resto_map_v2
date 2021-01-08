<?php
class MapsWrapper
{	
	public static $provider;
	
	public static function init($provider=array())
	{
		self::$provider = $provider;
	}
	
	public static function geoCodeAdress($address='')
	{
		$provider = self::$provider;
		if(!is_array($provider)){
			$provider = FunctionsV3::getMapProvider();
		}
					
		switch ($provider['provider']) {
			case "google.maps":				
				$protocol = isset($_SERVER["https"]) ? 'https' : 'http';
			    if ($protocol=="http"){
				   $api="http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address);
			    } else $api="https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address);
				
			    $key=Yii::app()->functions->getOptionAdmin('google_geo_api_key');		
				if ( !empty($key)){
					$api="https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".urlencode($key);
				}	
								
				$json=Yii::app()->functions->Curl($api,'');
				
				if ($resp = json_decode($json,true)){					
					if(isset($resp['error_message'])){
						throw new Exception( Yii::t("default","Error : [error_message] [status]",array(
						 '[error_message]'=>$resp['error_message'],
						 '[status]'=>$resp['status'],
						)) );
					} elseif (isset($resp['status'])){						
						$resp['status'] = trim($resp['status']);						
						if($resp['status']=="OK"){
							return array(
							  'lat'=>$resp['results'][0]['geometry']['location']['lat'],
							  'long'=>$resp['results'][0]['geometry']['location']['lng'],
							);
						} elseif ( $resp['status'] =="ZERO_RESULTS") {
							throw new Exception( Yii::t("default","Error : cannot geocode address [response]",array(
							 '[response]'=>$resp['status']
							)) );
						} else {
							return array(
							  'lat'=>$resp['results'][0]['geometry']['location']['lat'],
							  'long'=>$resp['results'][0]['geometry']['location']['lng'],
							);
						}
					} else {
						throw new Exception( Yii::t("default","Error : [response]",array(
						 '[response]'=>json_encode($resp)
						)) );
					}
				} else throw new Exception( Yii::t("default","Invalid response: [resp]",array(
				  '[resp]'=>$json
				)) );					
				break;
		
			case "mapbox":
				
				try {
					
					Yii::app()->setImport(array(			
					   'application.vendor.mapbox.*',
					));	
					require_once('mapbox/Mapbox.php');
					$mapbox = new Mapbox($provider['token']);
					$res = $mapbox->geocode($address);			   
			        $success = $res->success() ;		
			        $count = $res->getCount();
			        
			        if($success && $count>0){
			           $relevance=array();
			   	       $data = array();
			   	       foreach ($res as $key => $val) {			   	 				   	 	
			   	 	     $data[$key]=$val;
			   	 	     $relevance[$key]=$val['relevance'];
			   	       }
			   	       
			   	       $value = max($relevance);			   	 
			   	       $key = array_search($value, $relevance);
			   	       
			   	       if($key>=0){
			   	       	  if(isset($data[$key]['geometry'])){
			   	       	  	 $long = $data[$key]['geometry']['coordinates'][0];
				   	 		 $lat = $data[$key]['geometry']['coordinates'][1];
				   	 		 return array(
				   	 		   'lat'=>$lat,
			                   'long'=>$long
				   	 		 );
			   	       	  } else  throw new Exception( Yii::t("default","Invalid response: [resp]",array(
					         '[resp]'=>json_encode($res)
					    )));	 
			   	       } else throw new Exception( Yii::t("default","Invalid response: [resp]",array(
					         '[resp]'=>json_encode($res)
					    )));			   	       
			        } else throw new Exception( Yii::t("default","Invalid response: [resp]",array(
				         '[resp]'=>json_encode($res)
				    )));		
					
				} catch(Exception $e) {	
					throw new Exception($e->getMessage());
				}				
				break;
				
			default:
				throw new Exception( t("Invalid map provider") );
				break;
		}
	}	
	
	/*UNIT = M*/
	public static function getDistance($from_lat=0, $from_long=0, $to_lat=0, $to_long=0,$unit='',$mode='driving')
	{
		$provider = self::$provider;
		if(!is_array($provider)){
			$provider = FunctionsV3::getMapProvider();
		}
		
		if(empty($from_lat)){
			throw new Exception( t("Invalid latitude") );
		}
		if(empty($from_long)){
			throw new Exception( t("Invalid longititude") );
		}
		if(empty($to_lat)){
			throw new Exception( t("Invalid latitude") );
		}
		if(empty($to_long)){
			throw new Exception( t("Invalid longititude") );
		}
				
		$token = isset($provider['token'])?$provider['token']:'';
				
		if($provider['map_distance_results']==1){
			$provider['provider']='local';
		}
						
		switch ($provider['provider']) {
			case "google.maps":
				$api  = 'https://maps.googleapis.com/maps/api/distancematrix/json?';
				$params=array(
				  'key'=>trim($token),
				  'origins'=>("$from_lat,$from_long"),
				  'destinations'=>("$to_lat,$to_long"),
				  'units'=>$unit=="M"?"imperial":'metric',
				  'mode'=>$mode,
				  'departure_time'=>"now"
				);						
				$api.=http_build_query($params);				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                	throw new Exception( Yii::t("default","Error: [error]",array(
                	  '[error]'=>curl_error($ch)
                	)) );
                }                
                curl_close($ch);
                if ($json = json_decode($result,true)){                	
                	if($json['status']=="OK"){
                		foreach ($json['rows'] as $val) {                			        		
                			$status = isset($val['elements'][0]['status'])?$val['elements'][0]['status']:'';                			
                			switch (strtolower($status)) {
                				case "ok":
                					$distance=0;
                					$elements = $val['elements'][0];                				
                					$value = $elements['distance']['value'];
                					
                					if($unit=="M"){
                						$distance= (integer) $value*0.000621371192;
                					} else $distance= (integer) $value*0.001;
                					
                					$distance = number_format($distance,1,'.','');          	
                									
                					if($distance<=0){                						
                						if($unit=="M"){
                						    $unit='ft';
                					    } else $unit='m';
                					}
                					                					
                					$duration = isset($elements['duration'])?$elements['duration']['value']:'';
                					$duration_in_traffic = isset($elements['duration_in_traffic'])?$elements['duration_in_traffic']['value']:'';
                					
                					$duration = self::seconds2human( (integer) $duration);
                					$duration_in_traffic = self::seconds2human( (integer) $duration_in_traffic);
                					
                					$pretty_distance = Yii::t("default","[distance] [unit]",array(
			    					  '[distance]'=>$distance,
			    					  '[unit]'=>self::prettyUnit($unit)
			    					));
                					
                					return array(
                					  'distance'=>$distance,
                					  'unit'=>$unit,
                					  'pretty_unit'=>self::prettyUnit($unit),
                					  'pretty_distance'=>$pretty_distance,
                					  'distance_from_google'=>$elements['distance']['text'],
                					  'meters'=>$value,
                					  'duration'=>$duration,
                					  'duration_in_traffic'=>$duration_in_traffic,
                					  'provider'=>$provider['provider']
                					);
                					break;
                			
                				default:
                					 throw new Exception( Yii::t("default","An error has occured while getting distance, error: [error]",array(
	                				'[error]'=>$val['elements'][0]['status']
	                				)) );
                					break;
                			}
                		}
                	} else {
                		throw new Exception( t($json['error_message']) );
                	}
                } else throw new Exception( t("Failed response is not an array") );
				break;
				
			case "mapbox":				    			
			    $origin = "$from_long,$from_lat";
			    $destination ="$to_long,$to_lat";
			    
			    $ch = curl_init();
			    $api="https://api.mapbox.com/optimized-trips/v1/mapbox/$mode/$origin;$destination";
			    $api.="?access_token=$token";			    
			    curl_setopt($ch, CURLOPT_URL, $api );			    
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			    $result = curl_exec($ch);
                if (curl_errno($ch)) {
                	throw new Exception( Yii::t("default","Error: [error]",array(
                	  '[error]'=>curl_error($ch)
                	)) );
                }                
                curl_close($ch);                
                if ($json = json_decode($result,true)){                    	
                	if(!isset($json['code'])){
                		$json['code']='';
                	}
                	if(strtolower($json['code'])=="ok"){
                		$distance = 0;
                		$trips = $json['trips'][0];     
                		
                		$value = $trips['legs'][0]['distance'];
                		$duration = $trips['legs'][0]['duration'];
                		
                		if($unit=="M"){
    						$distance= (integer) $value*0.000621371192;
    					} else $distance= (integer) $value*0.001;
    					
    					$distance = number_format($distance,1,'.','');
    					
    					if($distance<=0){                						
    						if($unit=="M"){
    						    $unit='ft';
    					    } else $unit='m';
    					}
    					
    					$pretty_distance = Yii::t("default","[distance] [unit]",array(
    					  '[distance]'=>$distance,
    					  '[unit]'=>self::prettyUnit($unit)
    					));
    					    					
    					
    					$duration = self::seconds2human((integer)$duration);
    					
    					return array(
    					  'distance'=>$distance,
    					  'unit'=>$unit,
    					  'pretty_unit'=>self::prettyUnit($unit),
    					  'pretty_distance'=> $pretty_distance ,
    					  'meters'=>$value,
    					  'duration'=>$duration,
    					  'duration_in_traffic'=>'',
    					  'provider'=>$provider['provider']
    					);
    					                		
                	} else {
                		throw new Exception( Yii::t("default","An error has occured while getting distance, error: [error]",array(
        				'[error]'=>$json['message']
        				)) );    					
                	}
                } else throw new Exception( t("Failed response is not an array") );
			    break;
			    
			   case "local":			   	
			   	$distance = self::getLocalDistance($unit,$from_lat,$from_long,$to_lat,$to_long);			   	
			   	$pretty_distance = Yii::t("default","[distance] [unit]",array(
    					  '[distance]'=>$distance,
    					  '[unit]'=>self::prettyUnit($unit)
    					));
    					
    			$value=0;$duration=0;
    					
			   	return array(
				  'distance'=>$distance,
				  'unit'=>$unit,
				  'pretty_unit'=>self::prettyUnit($unit),
				  'pretty_distance'=> $pretty_distance ,
				  'meters'=>$value,
				  'duration'=>$duration,
				  'duration_in_traffic'=>'',
				  'provider'=>$provider['provider']
				);	
			   	break;
		
			default:
				throw new Exception( t("Invalid map provider") );
				break;
		}
						
	}
	
    public static function getLocalDistance($unit='', $lat1='',$lon1='', $lat2='', $lon2='')
    {    	  
    	  if(!is_numeric($lat1)){
    	  	 return 0;
    	  }
    	  if(!is_numeric($lon1)){
    	  	 return false;
    	  }
    	  if(!is_numeric($lat2)){
    	  	 return 0;
    	  }
    	  if(!is_numeric($lon2)){
    	  	 return 0;
    	  }
    	  $theta = $lon1 - $lon2;
    	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    	 
    	  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);
		  
		  $resp = 0;
		  		  
		  if ($unit == "K") {
		      $resp = ($miles * 1.609344);
		  } else if ($unit == "N") {
		      $resp = ($miles * 0.8684);
		  } else {
		      $resp = $miles;
		  }		  
		  
		  if($resp>0){
		  	 $resp = number_format($resp,1,".","");
		  }
		  
		  return $resp;
    }	
	
	public static function seconds2human($ss) {
		$s = $ss%60;
		$m = floor(($ss%3600)/60);
		$h = floor(($ss%86400)/3600);
		$d = floor(($ss%2592000)/86400);
		$M = floor($ss/2592000);		
		if($d>0){
			return Yii::t("default","[day] day [h] hr [m] mins",array(
			  '[day]'=>$d,
			  '[h]'=>$h,
			  '[m]'=>$m
			));
		} elseif ( $h>0 ) {		
			return Yii::t("default","[h] hr [m] mins",array(			  
			  '[h]'=>$h,
			  '[m]'=>$m
			));
		} else if ($m>0) {
			return Yii::t("default","[m] mins",array(			  			 
			  '[m]'=>$m
			));
		} else {
			return Yii::t("default","[m] mins",array(			  			 
			  '[m]'=>$m
			));
		}		
	}
	
	public static function prettyUnit($unit='')
	{
		switch ($unit) {
			case "M":		
			case "mi":	
			    return t("miles");
				break;
				
			case "K":			
			case "km":	
			    return t("km");
				break;	
				
			case "m":			
			    return t("m");
				break;		
				
			case "ft":			
			    return t("ft");
				break;			
		
			default:
				return $unit;
				break;
		}
	}
	
	public static function prettyDistance($distance=0,$unit='')
	{
		$distance =  number_format((float)$distance,1,".","");
		if(!empty($unit)){
			return "$distance $unit";
		}
		return $distance;
	}
	
}
/*end class*/