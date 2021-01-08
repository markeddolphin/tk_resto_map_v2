<?php
class StripeWrapper
{
	public static function paymentCode()
	{
		return 'stp';
	}

	public static function getAdminCredentials()
	{
		$enabled = false; $mode=''; 
		$secret_key = ''; $publish_key='';
		$webhook_secret='';
		
		$enabled = getOptionA('admin_stripe_enabled');
		$mode = getOptionA('admin_stripe_mode');		
		$card_fee = getOptionA('admin_stripe_card_fee');				
		
		$mode= strtolower($mode);
		switch ($mode){
			case "live":
				$secret_key = getOptionA('admin_live_stripe_secret_key');
				$publish_key = getOptionA('admin_live_stripe_pub_key');
				$webhook_secret=getOptionA('admin_live_stripe_webhooks');
				break;
				
			case "sandbox":
				$secret_key = getOptionA('admin_sanbox_stripe_secret_key');
				$publish_key = getOptionA('admin_sandbox_stripe_pub_key');
				$webhook_secret=getOptionA('admin_sandbox_stripe_webhooks');
				break;	
		}
										
		if($enabled=="yes" && !empty($secret_key) && !empty($publish_key) ){
			return array(
			  'mode'=>$mode,
			  'card_fee'=>$card_fee,
			  'secret_key'=>$secret_key,
			  'publish_key'=>$publish_key,
			  'webhook_secret'=>$webhook_secret
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
		$secret_key = ''; $publish_key='';
		$webhook_secret='';
		
		if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){			
			
			$enabled = getOptionA('admin_stripe_enabled');
			$mode = getOptionA('admin_stripe_mode');		
			$card_fee = getOptionA('admin_stripe_card_fee');		
			
			$mode= strtolower($mode);
			switch ($mode){
				case "live":
					$secret_key = getOptionA('admin_live_stripe_secret_key');
					$publish_key = getOptionA('admin_live_stripe_pub_key');
					$webhook_secret = getOptionA('admin_live_stripe_webhooks');
					break;
					
				case "sandbox":
					$secret_key = getOptionA('admin_sanbox_stripe_secret_key');
					$publish_key = getOptionA('admin_sandbox_stripe_pub_key');
					$webhook_secret = getOptionA('admin_sandbox_stripe_webhooks');
					break;	
			}		
				
		} else {	
						
			$enabled = getOption($merchant_id,'stripe_enabled');
			$mode = getOption($merchant_id,'stripe_mode');		
			$card_fee = getOption($merchant_id,'merchant_stripe_card_fee');		

			$mode= strtolower($mode);
			switch ($mode){
				case "live":
					$secret_key = getOption($merchant_id,'live_stripe_secret_key');
					$publish_key = getOption($merchant_id,'live_stripe_pub_key');
					$webhook_secret = getOption($merchant_id,'merchant_live_stripe_webhooks');
					break;
					
				case "sandbox":
					$secret_key = getOption($merchant_id,'sanbox_stripe_secret_key');
					$publish_key = getOption($merchant_id,'sandbox_stripe_pub_key');
					$webhook_secret = getOption($merchant_id,'merchant_sandbox_stripe_webhooks');
					break;	
			}				
		}		
		
		if($enabled=="yes" && !empty($secret_key) && !empty($publish_key) ){		
			return array(
			  'mode'=>$mode,
			  'card_fee'=>$card_fee,
			  'secret_key'=>$secret_key,
			  'publish_key'=>$publish_key,
			  'webhook_secret'=>$webhook_secret
			);
		}
		return false;
	}
	
	public static function createSession($secret='',$params=array())
	{		
		require_once('stripe/init.php');
		try {
			\Stripe\Stripe::setApiKey("$secret");		
			$resp = \Stripe\Checkout\Session::create($params);		
			return array(
			 'id'=>$resp->id,			 
			 'payment_intent'=>$resp->payment_intent
			);
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}				
	}
	
	public static function retrievePaymentIntent($secret='', $id='')
	{
		require_once('stripe/init.php');    	
		try {
	    	\Stripe\Stripe::setApiKey("$secret");		        	    	
	    	$resp = \Stripe\PaymentIntent::retrieve($id);
	    	return $resp;
    	} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}				
	}
	
	/*public static function createWebHook($secret='')
	{
		require_once('stripe/init.php');	
		try {
			\Stripe\Stripe::setApiKey("$secret");				
			$endpoint = \Stripe\WebhookEndpoint::create([
			  "url" => "http://bastisapp.com/testcode/",
			  "enabled_events" => ["charge.failed", "charge.succeeded"]
		    ]);
		    dump($endpoint);
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}				
	}*/
	
}/* end class*/