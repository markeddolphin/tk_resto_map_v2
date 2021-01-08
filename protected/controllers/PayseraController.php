<?php
if (!isset($_SESSION)) { session_start(); }

class PayseraController extends CController
{
	public $layout='merchant_tpl';	
	public $crumbsTitle='';
		
	public function actionIndex()
	{			
	    $db_ext=new DbExt;		
	    
		$error='';
		$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
		$amount_to_pay=0;
		
		$back_url=Yii::app()->request->baseUrl."/merchant/purchasesms";
		$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{sms_package_trans}}');		
		$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
		
		$merchant_id=Yii::app()->functions->getMerchantID();		
		
		if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
			$amount_to_pay=$res['price'];
			if ( $res['promo_price']>0){
				$amount_to_pay=$res['promo_price'];
			}	    										
			$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';	
			$payment_description.=isset($res['title'])?$res['title']:'';		
			
			/*dump($payment_description);
			dump($amount_to_pay);
			dump($payment_ref);*/
						
			$amount_to_pay=number_format($amount_to_pay,2,'.','');	
			
            $cancel_url=Yii::app()->getBaseUrl(true)."/merchant/purchasesms";
            
            $accepturl=Yii::app()->getBaseUrl(true)."/merchant/pysinit/?type=purchaseSMScredit&package_id=".
            $package_id."&mode=accept&mtid=$merchant_id";	
                        
            $callback=Yii::app()->getBaseUrl(true)."/paysera/?type=purchaseSMScredit&package_id=".
            $package_id."&mode=callback&mtid=$merchant_id";	
			
			$country=Yii::app()->functions->getOptionAdmin('admin_paysera_country');
		    $mode=Yii::app()->functions->getOptionAdmin('admin_paysera_mode');
		    $lang=Yii::app()->functions->getOptionAdmin('admin_paysera_lang');
		    $currency=Yii::app()->functions->adminCurrencyCode();	  
		    $projectid=Yii::app()->functions->getOptionAdmin('admin_paysera_project_id');		  
		    $password=Yii::app()->functions->getOptionAdmin('admin_paysera_password');
					    
		    if (isset($_GET['mode'])){				    	
		    	
		    	if ($_GET['mode']=="accept"){
		    		
	    		    $payment_code=Yii::app()->functions->paymentCode("paysera");
				  	$params=array(
						  'merchant_id'=>$_GET['mtid'],
						  'sms_package_id'=>$package_id,
						  'payment_type'=>$payment_code,
						  'package_price'=>$amount_to_pay,
						  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
						  'date_created'=>FunctionsV3::dateNow(),
						  'ip_address'=>$_SERVER['REMOTE_ADDR'],
						  'payment_gateway_response'=>json_encode($_GET),						  
						  //'payment_reference'=>$response['orderid']
					 );							 					
					 $db_ext->insertData("{{sms_package_trans}}",$params);		    		
		    		 header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
		    		 die();		    		 
		    	}
		    			    			    			    			    	   
		    	try {
		    		
		    		$response = WebToPay::checkResponse($_GET, array(
		              'projectid'     => $projectid,
		              'sign_password' => $password,
		            ));      
		            		            
		            if (is_array($response) && count($response)>=1){  
		            	
		            	if ($response['status']==0){
		            		die("payment has no been executed");
		            	}
		            	if ($response['status']==3){
		            		die("additional payment information");
		            	}		    
		            			            			            	 
		            	$stmt="SELECT * FROM
		            	{{sms_package_trans}}
		            	WHERE
		            	merchant_id = ".FunctionsV3::q($_GET['mtid'])."
		            	AND
		            	sms_package_id= ".FunctionsV3::q($_GET['package_id'])."
		            	ORDER BY id DESC
		            	LIMIT 0,1
		            	";		            	
		            	if ( $res2=$db_ext->rst($stmt)){		            		
		            		$current_id=$res2[0]['id'];
		            		$params_update=array('status'=>"paid");
		            		$db_ext->updateData("{{sms_package_trans}}",$params_update,'id',$current_id);
		            	}		            
						echo 'OK';
            	        die();
            	         		            	
		            } else $error=t("ERROR: api returns empty");	
		    		
		    	} catch (WebToPayException $e) {
	               $error=t("ERROR: Something went wrong").". ".$e;
	            }    			    	
		    } else {
				echo 'init';
		    }
		} else $error=Yii::t("default","Failed. Cannot process payment");  
				
		if (!empty($error)){
			//$this->render('error',array('message'=>$error));
			echo $error;
		}		
		
		//$this->createLogs('');		
	}	
	
	public function createLogs($response){    
    $myFile=dirname(__FILE__);
	    $myFile.= "/logs/callback-". date("Y-m-d")    . '.txt';            
	    $fh = @fopen($myFile, 'a');
	    $stringData .= 'URL=>'.$_SERVER['REQUEST_URI'] . "\n";    
	    $stringData .= 'IP ADDRESS=>'.$_SERVER['REMOTE_ADDR'] . "\n";     
	    $stringData .= 'DATE =>'.date("Y-m-d g:h i") . "\n";     
	    $stringData .= 'POST VAR=>'. json_encode($_REQUEST) . "\n";  
	    $stringData .= 'RESPONSE =>'. json_encode($response) . "\n";  
	    $stringData .=  "\n"; 
	    fwrite($fh, $stringData);                         
	    fclose($fh);  
	}/* END create_logs*/
		
}
/*END CONTROLLER*/
