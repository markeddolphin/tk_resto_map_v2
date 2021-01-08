<?php
$db_ext=new DbExt;
$data_get=$_GET;
$error='';
$success='';
$amount_to_pay=0;
$token=isset($_GET['token'])?$_GET['token']:'';
$back_url=Yii::app()->request->baseUrl."/store/merchantSignup/Do/step3/token/".$token;

$payment_description='';
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{package_trans}}');

$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	

$extra_params='';
if (isset($_GET['renew'])){
	$extra_params="/renew/1/package_id/".$package_id;
}

if ( $res=Yii::app()->functions->getMerchantByToken($my_token)){ 		
	$merchant_id=$res['merchant_id'];
	$payment_description="Membership Package - ".$res['package_name'];
	$amount_to_pay=normalPrettyPrice($res['package_price']);
	
	$amount_to_pay=number_format($amount_to_pay,2,'.','');	
	/*dump($merchant_id);
	dump($payment_description);
	dump($amount_to_pay);
	dump($payment_ref);*/
			
	$cancel_url=Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3/token/$my_token/gateway/pys".$extra_params;	
	$accepturl=Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3b/token/$my_token/gateway/pys/mode/accept".$extra_params;	
	$callback=Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3b/token/$my_token/gateway/pys/mode/callback".$extra_params;
	
	$country=Yii::app()->functions->getOptionAdmin('admin_paysera_country');
    $mode=Yii::app()->functions->getOptionAdmin('admin_paysera_mode');
    $lang=Yii::app()->functions->getOptionAdmin('admin_paysera_lang');
    $currency=Yii::app()->functions->adminCurrencyCode();	  
    $projectid=Yii::app()->functions->getOptionAdmin('admin_paysera_project_id');		  
    $password=Yii::app()->functions->getOptionAdmin('admin_paysera_password');
	
	if (isset($_GET['mode'])){
		/*echo 'here mode';
		dump($_GET);*/
		try {
						
			$response = WebToPay::checkResponse($_GET, array(
              'projectid'     => $projectid,
              'sign_password' => $password,
            ));
            //dump($response); 
                                   
            if (is_array($response) && count($response)>=1){          
            	
            	if ($response['status']==0){
            		?><p class="uk-text-danger"><?php echo t("payment has no been executed")?></p><?php
            		return ;
            	}
            	if ($response['status']==3){
            		?><p class="uk-text-danger"><?php echo t("additional payment information")?></p><?php
            		return ;
            	}
            	
            	if (isset($_GET['renew'])){   
            		
            		if ($new_info=Yii::app()->functions->getPackagesById($package_id)){	    					
						$res['package_name']=$new_info['title'];
						$res['package_price']=$new_info['price'];
						if ($new_info['promo_price']>0){
							$res['package_price']=$new_info['promo_price'];
						}			
					}
																			
					$membership_info=Yii::app()->functions->upgradeMembership($res['merchant_id'],$package_id);
																		
    				$params=array(
			          'package_id'=>$package_id,	          
			          'merchant_id'=>$res['merchant_id'],
			          'price'=>$res['package_price'],
			          'payment_type'=>Yii::app()->functions->paymentCode('paysera'),
			          'membership_expired'=>$membership_info['membership_expired'],
			          'date_created'=>FunctionsV3::dateNow(),
			          'ip_address'=>$_SERVER['REMOTE_ADDR'],
			          'PAYPALFULLRESPONSE'=>json_encode($response),
			          'TRANSACTIONID'=>$response['requestid'],
			          'TOKEN'=>$response['orderid']			          
			        );		
			        if ($response['status']==1){
			        	$params['status']='paid';
			        }
			             					      			        
			        $db_ext->insertData("{{package_trans}}",$params);	
			        
			        $params_update=array(
					  'package_id'=>$package_id,
					  'package_price'=>$membership_info['package_price'],
					  'membership_expired'=>$membership_info['membership_expired'],				  
					  'status'=>'active'
				 	 );		
				 	 
					 $db_ext->updateData("{{merchant}}",$params_update,'merchant_id',$res['merchant_id']);	  
					 					 
            	} else { 	
	            	$params=array(
			           'package_id'=>$res['package_id'],	          
			           'merchant_id'=>$res['merchant_id'],
			           'price'=>$res['package_price'],
			           'payment_type'=>Yii::app()->functions->paymentCode('paysera'),
			           'membership_expired'=>$res['membership_expired'],
			           'date_created'=>FunctionsV3::dateNow(),
			           'ip_address'=>$_SERVER['REMOTE_ADDR'],
			           'PAYPALFULLRESPONSE'=>json_encode($response),
			           'TRANSACTIONID'=>$response['requestid'],
			           'TOKEN'=>$response['orderid']			           
				     );			
				     if ($response['status']==1){
			        	$params['status']='paid';
			         }			        
			         $db_ext->insertData("{{package_trans}}",$params);	
			         
			         $db_ext->updateData("{{merchant}}",
											  array(
											    'payment_steps'=>3,
											    'membership_purchase_date'=>FunctionsV3::dateNow()
											  ),'merchant_id',$res['merchant_id']);
											  		         		    
		             $okmsg=Yii::t("default","transaction was susccessfull");	
            	 }
            	
            	 if (isset($_GET['renew'])){
                     header('Location: '.Yii::app()->request->baseUrl."/store/renewsuccesful");
                 } else header('Location: '.Yii::app()->request->baseUrl."/store/merchantSignup/Do/step4/token/$my_token");				     		     die();
                 
            } else $error=t("ERROR: api returns empty");
    
		} catch (WebToPayException $e) {
	       $error=t("ERROR: Something went wrong").". ".$e;
	    }    	
	} else {		
		try {				
		  
		  dump($amount_to_pay);
		  $params_request=array(
	        'projectid'     => $projectid,
	        'sign_password' => $password,
	        'orderid'       => $payment_ref,
	        'amount'        => $amount_to_pay*100,
	        'currency'      => $currency,
	        'country'       => $country,
	        'accepturl'     => $accepturl,
	        'cancelurl'     => $cancel_url,
	        'callbackurl'   => $callback,
	        'test'          => $mode,
	        'lang'          =>$lang
	       );	
	       if ($mode==2){
	       	   unset($params_request['test']);
	       }       
	       /*dump($params_request);
	       die();*/
		   $request = WebToPay::redirectToPayment($params_request);
			
		} catch (WebToPayException $e) {
	       $error=t("ERROR: Something went wrong").". ".$e;
	    }    
	}
} else $error=t("ERROR: Something went wrong");

if (!empty($error)){
	?>
	<p class="uk-text-danger"><?php echo $error?></p>
	<?php
}