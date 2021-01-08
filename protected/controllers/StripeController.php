<?php
class StripeController extends CController
{
	
	public function beforeAction($action)
	{		
		return true;
	}
	
	public function actionIndex()
	{
		
	}
	
    public function actionWebhooks()
	{		
		sleep(10);
		
		Yii::app()->setImport(array(			
		  'application.components.*',
		));		
		require_once 'Functions.php';
						
		$data = @file_get_contents('php://input');
		
		if(empty($data)){
		   echo 'data is empty';
		   http_response_code(400);
		   Yii::app()->end();
		}
		
		$db=new DbExt();
		$secret=''; 
		$endpoint_secret = '';
		
		$error =''; 
		$code=2;		
		$trans_type = ''; 
		
		$merchant_id=''; 
		$order_id='';
		$order_info = array();
		$credentials = array();
		
		$data=json_decode($data,true);
		
		$data = $data['data']['object'];
		$payment_intent = $data['payment_intent'];
		$client_reference_id = $data['client_reference_id'];
		$client_reference_explode = explode("-",$client_reference_id);		
		
		if(is_array($client_reference_explode) && count((array)$client_reference_explode)>=1){
			$trans_type = $client_reference_explode[0];
			$reference_id = $client_reference_explode[1];			
			switch ($trans_type) {
				case "order":
					if($order_info = FunctionsV3::getOrderByToken($reference_id)){						
						$merchant_id = $order_info['merchant_id'];
						$order_id = $order_info['order_id'];
						$client_id = $order_info['client_id'];
						if ( $credentials = StripeWrapper::getCredentials($merchant_id)){
							$secret = $credentials['secret_key'];
							$endpoint_secret = $credentials['webhook_secret'];
						} else $error = "invalid stripe credentials";
					} else $error = "order information not found";
					break;
			
				default:
					break;
			}
			
			if(!empty($secret)){
				
				require_once('stripe/init.php'); 
				\Stripe\Stripe::setApiKey($secret);
				
				$payload = @file_get_contents('php://input');
				$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
				$event = null;
				
				try {
					
					$event = \Stripe\Webhook::constructEvent(
				        $payload, $sig_header, $endpoint_secret
				    );	
				    
				   
				    switch ($event->type) {
				    	case "checkout.session.completed":		
				    	case "charge.succeeded":				    						    
				    				    		
				    		$code = 1;
				    		if($trans_type=="order"){
				    			if($order_info['status']=="initial_order"){
				    				
				    				FunctionsV3::updateOrderPayment(
				    				  $order_info['order_id'],
				    				  StripeWrapper::paymentCode(),
		        	    		      $payment_intent,
		        	    		      json_encode($payload),
		        	    		      $order_info['order_id_token']
		        	    		    );		   
		        	    		    
		        	    		    FunctionsV3::callAddons($order_info['order_id']);
		        	    		    
		        	    		    try {
		        	    		    	$print_resp = PrintWrapper::prepareReceipt($order_id);
		        	    		    	$print = $print_resp['print'];
		        	    		    	$print_data = $print_resp['data'];
		        	    		    	$print_additional_details = $print_resp['additional_details'];
		        	    		    	$print_raw = $print_resp['raw'];
		        	    		    	
		        	    		    	$to=isset($print_data['email_address'])?$print_data['email_address']:'';
	                                    $receipt=EmailTPL::salesReceipt($print, $print_resp['raw'] );	 
	                                    
	                                    FunctionsV3::notifyCustomer($print_data,$print_additional_details,$receipt, $to);
	                                    FunctionsV3::notifyMerchant($data,$print_additional_details,$receipt);
	                                    FunctionsV3::notifyAdmin($data,$print_additional_details,$receipt);
	                                    
	                                    FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("cron/processemail"));
	                                    FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("cron/processsms"));	  
		        	    		    		                                    
	                                    /*PRINTER ADDON*/
								        if (FunctionsV3::hasModuleAddon("printer")){
								        	Yii::app()->setImport(array('application.modules.printer.components.*'));
								        	$html=getOptionA('printer_receipt_tpl');
											if($print_receipt = ReceiptClass::formatReceipt($html,$print,$print_raw,$print_data)){							
												PrinterClass::printReceipt($order_id,$print_receipt);												
											}
											
											$html = getOption($merchant_id,'mt_printer_receipt_tpl');
											if($print_receipt = ReceiptClass::formatReceipt($html,$print,$print_raw,$print_data)){
										       PrinterClass::printReceiptMerchant($merchant_id,$order_id,$print_receipt);		
											}		
											FunctionsV3::fastRequest(FunctionsV3::getHostURL().Yii::app()->createUrl("printer/cron/processprint"));	
								        }
	                                    
		        	    		    } catch (Exception $e){
		        	    		    	$error = $e->getMessage();
		        	    		    }				    			
				    				
				    			} else $error = "order status is already ".$order_info['status'];	 
				    		} elseif ($trans_type=="reg"){
				    			
				    		} else $error = "invalid transaction type";				    		
				    		break;
				              
				    	default:
				    		  $error = "Unexpected event type $event->type";
				    		break;
				    }
					
				} catch(\UnexpectedValueException $e) {									   
				    $error = "Invalid payload ".json_encode($payload);	    				   
				} catch(\Stripe\Error\SignatureVerification $e) {				    
				    $error = "Invalid signature =>$endpoint_secret =>$sig_header";    				    
				}
				
			} else $error = "invalid secret id";
			
		} else $error = "client ref not an array";
		
		$response=array('code'=>$code,'message'=>$error,'client_reference_id'=>$client_reference_id);
		
		$db->insertData("{{stripe_logger}}",array(
		  'trans_type'=>$trans_type,
		  'payment_intent'=>$payment_intent,
		  'post_receive'=>json_encode($data),
		  'webhooks_response'=>json_encode($response),
		  'date_created'=>FunctionsV3::dateNow(),
		  'post_receive_date'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		));
		
		if(!empty($error)){
		   echo json_encode($response);
		   http_response_code(400);
		} else {
		   echo json_encode($response);
		   http_response_code(200);
		}			
	}
		
}/* end class*/