<?php
class CheckoutWrapper
{	
	public static function verifyCanPlaceOrder($client_id='')
	{
		$restrict_order_by_status = getOptionA('restrict_order_by_status');
		if(!empty($restrict_order_by_status)){			
			if ($json = json_decode($restrict_order_by_status,true)){
				$order_stats=''; 
				foreach ($json as $val) {
					$order_stats.=q($val).",";					
				}		
				if(!empty($order_stats)){
					$order_stats = substr($order_stats,0,-1);					
					$stmt = "SELECT order_id,status
					FROM {{order}}					
					WHERE 
					client_id = ".q($client_id)."
					AND
					status IN (".$order_stats.")
					ORDER BY order_id DESC
					LIMIT 0,1
					";					
					if($res = Yii::app()->db->createCommand($stmt)->queryRow()){						
						throw new Exception( tt("You have previous orders with status [status], you cannot place another order until your last order is process",array(
						  '[status]'=>t($res['status'])
						)) );
					}
				}
			}
		}
		return true;
	}
	
	public static function verifyLocation($merchant_id=0, $lat=0, $lng=0,$order_subtotal=0)
	{
		$resp = Yii::app()->db->createCommand()
          ->select('merchant_id,latitude,lontitude,minimum_order')
          ->from('{{merchant}}')        
          ->where("merchant_id=:merchant_id",array(
             ':merchant_id'=>(integer)$merchant_id,             
          )) 
          ->limit(1)   
          ->queryRow();	
        if($resp){
        	
        	$provider = FunctionsV3::getMapProvider();        	
        	MapsWrapper::init($provider);
        	$unit = FunctionsV3::getMerchantDistanceType($merchant_id); 
        	$mode = isset($provider['mode'])?$provider['mode']:'driving';
        		
        	        	
        	$merchant_delivery_distance = getOption($merchant_id,'merchant_delivery_miles');   
        	
        	/*GET DELIVERY FEE*/
        	$delivery_fee = getOption($merchant_id,'merchant_delivery_charges');    
        	$resp_distance = array();
        	       	
        	if($merchant_delivery_distance>0){
	        	$resp_distance = MapsWrapper::getDistance($resp['latitude'],$resp['lontitude'],$lat,$lng,$unit,$mode);
	        	
	        	$distance = $resp_distance['distance'];
	        	if($merchant_delivery_distance>0){
		        	if($distance>$merchant_delivery_distance){	        		
	        		   $pretty_distance = Yii::t("default","[distance] [unit]",array(
			    					  '[distance]'=>$merchant_delivery_distance,
			    					  '[unit]'=>MapsWrapper::prettyUnit($unit)
			    					));
		        		$error = Yii::t("default","Sorry but this merchant delivers only with in [distance] your current distance is [current_distance]",array(
		        		   '[distance]'=>$pretty_distance,
		        		   '[current_distance]'=>$resp_distance['pretty_distance']
		        		));
		        		throw new Exception( $error );
		        	} 
	        	}
	        	
	        	/*MINIMUM ORDER TABLE*/
	        	$min_tables_enabled = getOption($merchant_id,'min_tables_enabled');
	        	if($min_tables_enabled==1){	        			        		
	        		$min_order = self::getMinimumOrderTable(
	        		   $merchant_id,$resp_distance['distance'],$resp_distance['unit'],$resp['minimum_order']
	        		);	        		
	        		if($min_order>$order_subtotal){
	        			$error = Yii::t("default","Sorry but minimum order is [min_order] for distance [distance]",array(
						 '[min_order]'=>FunctionsV3::prettyPrice($min_order),
						 '[distance]'=>$resp_distance['pretty_distance']
					   ));
					   throw new Exception( $error );
	        		}
	        	}
	        	
	        	/*SHIPPING FEE*/
	        	$shipping_enabled = getOption($merchant_id,'shipping_enabled');
	        	if($shipping_enabled==2){	        		
	        		$delivery_fee = self::getShippingFee($merchant_id,$resp_distance['distance'],$resp_distance['unit'],$delivery_fee);
	        	}     	
        	} 
        	        	
        	return array_merge((array)$resp_distance, array('delivery_fee'=>$delivery_fee));
        	
        } else throw new Exception( t("Merchant not found") );
	}	
	
	/* PARAMETERS 
	array
		(
		    [merchant_id] => 1
		    [provider] => Array
		        (
		            [provider] => google.maps
		            [token] => AIzaSyBp5olQaiXZfnlRqsUGehhtBRId2rWcj8A
		            [map_api] => AIzaSyBAD37tZZ5mOqmkgV66o0_6tZ2-rhdZSmE
		            [map_distance_results] => 2
		            [mode] => driving
		        )
		
		    [from_lat] => 14.561682215800909
		    [from_lng] => 121.04538871772155
		    [to_lat] => 14.5437996
		    [to_lng] => 121.0115282
		    [delivery_charges] => 1.50000
		    [unit] => mi
		    [delivery_distance_covered] => 5.00
		    [order_subtotal] => 0,
		    ['minimum_order] => 10
		)
	*/
	public static function getDeliveryDetails($data=array())
	{											
		$merchant_id = $data['merchant_id'];
		$delivery_fee = $data['delivery_charges'];
		$unit = self::unit($data['unit']);		
		$mode = $data['provider']['mode'];		
		$merchant_delivery_distance = (float)$data['delivery_distance_covered'];		
		
		$resp_distance = MapsWrapper::getDistance($data['from_lat'],$data['from_lng'],$data['to_lat'],$data['to_lng'],$unit,$mode);
		if($resp_distance){			
			$distance = $resp_distance['distance'];			
			
			if($merchant_delivery_distance>0){
	        	if($distance>$merchant_delivery_distance){	        		
	    		   $pretty_distance = Yii::t("default","[distance] [unit]",array(
		    					  '[distance]'=>$merchant_delivery_distance,
		    					  '[unit]'=>MapsWrapper::prettyUnit($unit)
		    					));
	        		$error = Yii::t("default","Sorry but this merchant delivers only with in [distance] your current distance is [current_distance]",array(
	        		   '[distance]'=>$pretty_distance,
	        		   '[current_distance]'=>$resp_distance['pretty_distance']
	        		));
	        		$resp_distance['distance_error']=$error;
	        	} 
			}
        	
        	/*MINIMUM ORDER TABLE*/
        	$resp_distance['min_order']=0;
        	$min_tables_enabled = getOption($merchant_id,'min_tables_enabled');        	
        	if($min_tables_enabled==1){	        			        		
        		$min_order = self::getMinimumOrderTable(
        		$merchant_id,$resp_distance['distance'],$resp_distance['unit'],$data['minimum_order']);	
        		$resp_distance['min_order']=$min_order;
        	} else {
        		$resp_distance['min_order']=isset($data['minimum_order'])?$data['minimum_order']:0;
        	}
        	                
        	/*SHIPPING FEE*/
        	$shipping_enabled = getOption($merchant_id,'shipping_enabled');
        	if($shipping_enabled==2){	        		
        		$delivery_fee = self::getShippingFee($merchant_id,$resp_distance['distance'],$resp_distance['unit'],$delivery_fee);
        	}     	
        	
        	return array_merge((array)$resp_distance, array('delivery_fee'=>$delivery_fee));
		}
	}
	
	/*UNIT = M*/
	public static function getShippingFee($merchant_id='',$distance=0, $unit='', $delivery_fee=0)
	{
		$_unit = $unit=="M"?"mi":"km"; $_fee = $delivery_fee;			
		$resp = Yii::app()->db->createCommand()
          ->select('distance_from,distance_to,distance_price')
          ->from('{{shipping_rate}}')     
          ->where("merchant_id=:merchant_id AND shipping_units=:shipping_units",array(
             ':merchant_id'=>$merchant_id,
             ':shipping_units'=>$_unit,
          ))       
          ->order('distance_from ASC')    
          ->queryAll();		
         if($resp){
         	$last_record = array(); $found = false;
         	foreach ($resp as $val) {
         		if ( $val['distance_from']<=$distance && $val['distance_to']>=$distance){
         			$_fee = $val['distance_price'];
         			$found=true;
         		}
         		$last_record = $val;
         	}
         	
         	if($found==false){   
         		if($distance>$last_record['distance_to']){
         		  $_fee = $last_record['distance_price'];
         		}
         	}         	
         } 
         return $_fee;
	}
	
	 public static function unit($unit='')
    {    	
    	$type='';
    	$unit=strtolower($unit);        
        switch ($unit) {
        	case "mi":
        		$type="M";
        		break;        
        	case "km":	
        	    $type="K";
        	    break;
        	default:
        		$type="M";
        		break;
        }
        return $type;
    }
    
    /*UNIT = M*/
	public static function getMinimumOrderTable($merchant_id='',$distance=0, $unit='',$min_fee=0)
	{
		$_unit = $unit=="M"?"mi":"km"; $_fee = $min_fee;		
		$resp = Yii::app()->db->createCommand()
          ->select('distance_from,distance_to,min_order')
          ->from('{{minimum_table}}')     
          ->where("merchant_id=:merchant_id AND shipping_units=:shipping_units",array(
             ':merchant_id'=>$merchant_id,
             ':shipping_units'=>$_unit,
          ))       
          ->order('distance_from ASC')    
          ->queryAll();		
         if($resp){
         	$last_record = array(); $found = false;
         	foreach ($resp as $val) {         		
         		if ( $val['distance_from']<=$distance && $val['distance_to']>=$distance){
         			$_fee = $val['min_order'];
         			$found=true;
         		}
         		$last_record = $val;
         	}
         	
         	if($found==false){           
         		if ($distance>$last_record['distance_to']){ 		
         		    $_fee = $last_record['min_order'];
         		}
         	}         	
         } 
         return $_fee;
	}	
}
/*end class;*/
