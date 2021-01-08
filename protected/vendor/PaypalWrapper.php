<?php
require_once('Paypal-SDK/vendor/autoload.php');
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
        
class PaypalWrapper
{
	public static function paymentCode()
	{
		return 'paypal_v2';
	}

	public static function getAdminCredentials()
	{
		$enabled = false; $mode=''; 
		$client_id = ''; 
		$secret_key='';		
		
		$enabled = getOptionA('admin_paypal_v2_enabled');
		$mode = getOptionA('admin_paypal_v2_mode');		
		$card_fee = getOptionA('admin_paypal_v2_card_fee');				
		
		$client_id = getOptionA('admin_paypal_v2_client_id');
		$secret_key = getOptionA('admin_paypal_v2_secret');
										
		if($enabled==1 && !empty($client_id) && !empty($secret_key) ){
			return array(
			  'mode'=>$mode,
			  'card_fee'=>$card_fee,
			  'client_id'=>trim($client_id),
			  'secret_key'=>trim($secret_key)
			);
		}
		return false;
	}
	
	public static function getCredentials($merchant_id='')
	{
						
		if($merchant_id<0 || empty($merchant_id)){
			return false;
		}
		
		$enabled = false; $mode=''; 
		$client_id = ''; 
		$secret_key='';		
		
		if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){			
			
			$enabled = getOptionA('admin_paypal_v2_enabled');
			$mode = getOptionA('admin_paypal_v2_mode');		
			$card_fee = getOptionA('admin_paypal_v2_card_fee');				
			
			$client_id = getOptionA('admin_paypal_v2_client_id');
			$secret_key = getOptionA('admin_paypal_v2_secret');
				
		} else {	
						
			$enabled = getOption($merchant_id,'merchant_paypal_v2_enabled');
			$mode = getOption($merchant_id,'merchant_paypal_v2_mode');		
			$card_fee = getOption($merchant_id,'merchant_paypal_v2_card_fee');
			
			$client_id = getOption($merchant_id,'merchant_paypal_v2_client_id');
			$secret_key = getOption($merchant_id,'merchant_paypal_v2_secret');		
			
		}		
		
		if($enabled==1 && !empty($client_id) && !empty($secret_key) ){
			return array(
			  'mode'=>$mode,
			  'card_fee'=>$card_fee,
			  'client_id'=>trim($client_id),
			  'secret_key'=>trim($secret_key)
			);
		}
		return false;
	}
	
	public static function createOrder($client_id='', $secret='', $mode='sandbox', $params=array())
	{		       
						
		if($mode=="live"){
			$environment = new ProductionEnvironment($client_id, $secret);
		} else $environment = new SandBoxEnvironment($client_id, $secret); 
		
        $client = new PayPalHttpClient($environment);
        
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        
        $request->body = $params;       
        
        try {
        	$response = $client->execute($request);
        	//dump($response);die();
        	$data=array();
        	foreach($response->result->links as $link){        		
        		$data[$link->rel]=$link->href;
        	}
        	return $data;
        } catch (HttpException $ex) {
        	throw new Exception( $ex->getMessage() );
        }
             
	}
	
	public static function captureRequest($client_id='', $secret='', $mode='sandbox',$payment_token='')
	{				
		if($mode=="live"){
			$environment = new ProductionEnvironment($client_id, $secret);
		} else $environment = new SandBoxEnvironment($client_id, $secret); 
		
	    $client = new PayPalHttpClient($environment);
	        
		try {
												
			$request = new OrdersCaptureRequest($payment_token);
			$request->prefer('return=representation');
			
			$response = $client->execute($request);
									
			return array(
			  'status_code'=>$response->statusCode,
			  'status'=>$response->result->status,
			  'id'=>$response->result->id,
			);
			
		} catch (HttpException $ex) {			
			throw new Exception( $ex->statusCode );
		}
	}
		
}/* end class*/