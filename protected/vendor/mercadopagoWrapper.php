<?php
class mercadopagoWrapper
{
	static $message;
	
	public static function paymentCode()
	{
		return 'mercadopago';
	}
	
	public static function getCredentials($merchant_id='')
	{
		$enabled=false;  $mode=''; $card_fee = 0; $client_id=''; $client_secret='';
		
    	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
    		// USER ADMIN SETTINGS
    		$enabled =getOptionA('mercadopago_v2_enabled'); 
    		$mode = getOptionA('mercadopago_v2_mode');
    		$client_id = getOptionA('admin_mercadopago_v2_client_id');
    		$client_secret = getOptionA('admin_mercadopago_v2_client_secret');
    		$card_fee = getOptionA('admin_mercadopago_v2_card_fee');    		
    	} else {
    		// USE MERCHANT SETTINGS    	
    		$enabled =getOption($merchant_id,'merchant_mercadopago_v2_enabled'); 	    		    		
    		$mode = getOption($merchant_id,'merchant_mercadopago_v2_mode');
    		$card_fee = getOption($merchant_id,'merchant_mercadopago_v2_card_fee');    	
    		$client_id = getOption($merchant_id,'merchant_mercadopago_v2_client_id');    	
    		$client_secret = getOption($merchant_id,'merchant_mercadopago_v2_client_secret');    	
    	}    	
    	if($enabled==1){
    		return array(
    		   'enabled'=>$enabled,
    		   'mode'=>trim($mode),       		   
    		   'card_fee'=>$card_fee,
    		   'client_id'=>$client_id,
    		   'client_secret'=>$client_secret,
    		);
    	}
    	return false;
	}
	
	public static function getAdminCredentials()
	{		
		$enabled=false;  $mode=''; $card_fee = 0; $client_id=''; $client_secret='';
		
		$enabled =getOptionA('mercadopago_v2_enabled'); 
    	$mode = getOptionA('mercadopago_v2_mode');    	
    	$card_fee = getOptionA('admin_mercadopago_v2_card_fee');
    	$client_id = getOptionA('admin_mercadopago_v2_client_id');
    	$client_secret = getOptionA('admin_mercadopago_v2_client_secret');    		
    		
		if($enabled==1){
    		return array(
    		   'enabled'=>$enabled,
    		   'mode'=>trim($mode),       		   
    		   'card_fee'=>$card_fee,
    		   'client_id'=>$client_id,
    		   'client_secret'=>$client_secret,
    		);
    	}
    	return false;
	}
	
	public static function createPayment($credentials=array(),$params=array())
	{		
		require_once 'dx-mercapago/vendor/autoload.php';		
		MercadoPago\SDK::setClientId($credentials['client_id']);
        MercadoPago\SDK::setClientSecret($credentials['client_secret']);
        
        $preference = new MercadoPago\Preference();
        $preference->external_reference = $params['external_reference'];
        $preference->back_urls = array(
		    "success" => $params['success'],
		    "failure" => $params['failure'],
		    "pending" => $params['pending'],
		);
		$preference->auto_return = "approved";

        $item = new MercadoPago\Item();	    
	    $item->title = $params['title'];
	    $item->quantity = $params['quantity'];
	    $item->currency_id = $params['currency_id'];
	    $item->unit_price = $params['unit_price'];
	    
	    $payer = new MercadoPago\Payer();
	    $payer->email = $params['email'];
	    
	    $preference->items = array($item);
	    $preference->payer = $payer;	    

	    if ($preference->save()) {
	    	$payment_link = '';
		    if(strtolower($credentials['mode'])=="sandbox"){
		    	$payment_link = $preference->sandbox_init_point;
		    } else {
		    	$payment_link = $preference->init_point;
		    }
		    return $payment_link;
	    } else {	    	    
	    	throw new Exception( $preference->error->error );
	    }
	    	   
	}
	
	public static function getPaymentStatus($credentials=array(),$external_reference='')
	{		
		require_once 'dx-mercapago/vendor/autoload.php';		
		MercadoPago\SDK::setClientId($credentials['client_id']);
        MercadoPago\SDK::setClientSecret($credentials['client_secret']);
        
        try {        	
	        $filters = array(
		      "external_reference" => trim($external_reference)
		    );		    
		    $options = array(
			"limit" => "1",
			"offset" => "0"
			);
			$payment = MercadoPago\Payment::search($filters);				
		    return true;
		    		    
	    } catch(Exception $e) {
	    	throw new Exception( $e->getMessage() );
	    }			
	}
			
}/* end class*/