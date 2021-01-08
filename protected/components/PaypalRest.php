<?php
class PaypalRest
{
	public $client_id;
	public $secret_id;
	public $message;	
	public $token;
	
	public function __construct(){		
	}

	public function getAccessToken()
	{
		$ch = curl_init();		
				
		curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_USERPWD, $this->client_id.":".$this->secret_id);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		
		$result = curl_exec($ch);		
		if (!empty($result)){
			 $json = json_decode($result);			 
			 $this->token=$json->access_token;
             return $json->access_token;
		} else $this->message=t("Error: No response");		
		curl_close($ch);
	}
	
	public function payout()
	{
		$url="https://api.sandbox.paypal.com/v1/payments/payouts?sync_mode=true";
		$items[]=array(
		  'recipient_type'=>"EMAIL",
		  'amount'=>array(
		    'value'=>12.34,
		    'currency'=>"USD"
		  ),
		  'receiver'=>"buyer2@codemywebapps.com",
		  'note'=>"Payment for recent T-Shirt delivery",
		  'sender_item_id'=>"A123"
		);
		$params=array(
		  'sender_batch_header'=>array(		    
		    'email_subject'=>"You have a payment"
		  ),
		  'items'=>$items
		);				
		dump($params);				
		if ( $resp=$this->makePostCall($url,$params)){
			dump($resp);
		}		
	}
	
	public function makePostCall($url, $postdata) {
						
		$postdata=json_encode($postdata);
		/*echo "<h2>Request</h2>";		
		dump($url);
		dump($postdata);*/
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		   'Authorization: Bearer '.$this->token,
		   'Accept: application/json',
		   'Content-Type: application/json'
		));
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
		#curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
		
		$response = curl_exec( $curl );
		dump($response);
		if (empty($response)) {
		   // some kind of an error happened
		   die(curl_error($curl));
		   curl_close($curl); // close cURL handler
		} else {
		   $info = curl_getinfo($curl);		   
		   //echo "Time took: " . $info['total_time']*1000 . "ms\n";
		   curl_close($curl); // close cURL handler
		   if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
		      echo "Received error: " . $info['http_code']. "\n";
		      echo "Raw response:".$response."\n";
		      die();
		   }
		}
		// Convert the result from JSON format to a PHP array
		$jsonResponse = json_decode($response, TRUE);
		return $jsonResponse;
	}
	
	public function makeGetCall($url) {
		
	 	$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		  'Authorization: Bearer '.$this->token,
		  'Accept: application/json',
		  'Content-Type: application/json'
		));
		#curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
		$response = curl_exec( $curl );
		if (empty($response)) {
		   // some kind of an error happened
		   die(curl_error($curl));
		   curl_close($curl); // close cURL handler
		} else {
		   $info = curl_getinfo($curl);
		   echo "Time took: " . $info['total_time']*1000 . "ms\n";
		   curl_close($curl); // close cURL handler
		   if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
		      echo "Received error: " . $info['http_code']. "\n";
		      echo "Raw response:".$response."\n";
		      die();
		   }
		}
		// Convert the result from JSON format to a PHP array
		$jsonResponse = json_decode($response, TRUE);
		return $jsonResponse;
	}	
	
	public function getMessage()
	{
		return $this->message;
	}
		
} /*END class*/