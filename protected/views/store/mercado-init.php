<?php
$paymode=Yii::app()->functions->getOptionAdmin('admin_mercado_mode');
$admin_mercado_id=Yii::app()->functions->getOptionAdmin('admin_mercado_id');
$admin_mercado_key=Yii::app()->functions->getOptionAdmin('admin_mercado_key');

require_once 'mercadopago/mercadopago.php';
$db_ext=new DbExt;
$payment_code=Yii::app()->functions->paymentCode("mercadopago");
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';

$extra_params='';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
if (isset($_GET['renew'])){
	$extra_params="/renew/1/package_id/".$package_id;
}

if (isset($_GET['status'])){	
	$status=$_GET['status'];	
	
	$reference=isset($_GET['external_reference'])?$_GET['external_reference']:'';		
	
	if ( $status=="success" || $status=="pending"){		
		try {
			$mp = new MP($admin_mercado_id, $admin_mercado_key);	
			$filters = array(            
               "external_reference" => $reference
            );      
            //dump($filters);
            $searchResult = $mp->search_payment($filters);           
            //dump($searchResult);            
            if (is_array($searchResult) && count($searchResult)>=1){
            	if ($searchResult['status']==200){
            		//$ref=explode("-",$reference);            	
            		//$package_id=isset($ref[0])?$ref[0]:'';
            		if ( $res=Yii::app()->functions->getMerchantByToken($my_token)){ 
            			
            			if (isset($_GET['renew'])){            				
            				if ($new_info=Yii::app()->functions->getPackagesById($package_id)){								
            					//dump($new_info);
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
					          'payment_type'=>$payment_code,
					          'membership_expired'=>$membership_info['membership_expired'],
					          'date_created'=>FunctionsV3::dateNow(),
					          'ip_address'=>$_SERVER['REMOTE_ADDR'],
					          'PAYPALFULLRESPONSE'=>json_encode($_GET),
					          'TRANSACTIONID'=>$reference
					        );		
					        	     					        					       
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
					          'payment_type'=>$payment_code,
					          'membership_expired'=>$res['membership_expired'],
					          'date_created'=>FunctionsV3::dateNow(),
					          'ip_address'=>$_SERVER['REMOTE_ADDR'],
					          'PAYPALFULLRESPONSE'=>json_encode($_GET),
					          'TRANSACTIONID'=>$reference
					        );						        				    
            			}    
				        $db_ext->insertData("{{package_trans}}",$params);	
				        				        			        
				        $db_ext->updateData("{{merchant}}",
										  array(
										    'payment_steps'=>3,
										    'membership_purchase_date'=>FunctionsV3::dateNow()
										  ),'merchant_id',$res['merchant_id']);
										  
                        if (isset($_GET['renew'])){
                        	header('Location: '.Yii::app()->request->baseUrl."/store/renewsuccesful");
                        } else header('Location: '.Yii::app()->request->baseUrl."/store/merchantSignup/Do/step4/token/$my_token");				     
            		} else $error=Yii::t("default","Failed. Cannot process payment");            		        
                } else $error=Yii::t("default","Failed. Cannot process payment")." ".$searchResult['status'];
            } else $error=Yii::t("default","ERROR: Invalid response from Mercadopago");
            
		} catch (Exception $e){
			$error=$e->getMessage();
		}
			
	} else $error=Yii::t("default","Failed. Cannot process payment");
	
	if (!empty($error)){
		?>
		<div class="page-right-sidebar payment-option-page">
		    <div class="main">
		    <?php  echo "<p class=\"uk-text-danger\">".$error."</p>";?>
		    </div>
        </div>		    
		<?php	    
	}
	
} else  {
			
		
	$this->renderPartial('/front/banner-receipt',array(
	   'h1'=>t("Payment"),
	   'sub_text'=>t("step 3 of 4")
	));
	
	/*PROGRESS ORDER BAR*/
	$this->renderPartial('/front/progress-merchantsignup',array(
	   'step'=>3,
	   'show_bar'=>true
	));
	
	if ( $res=Yii::app()->functions->getMerchantByToken($my_token)){			
		
		if (isset($_GET['renew'])){
			if ($new_info=Yii::app()->functions->getPackagesById($package_id)){		    				
				$res['package_name']=$new_info['title'];
				$res['package_price']=$new_info['price'];
				if ($new_info['promo_price']>0){
					$res['package_price']=$new_info['promo_price'];
				}			
			}	
		}
		
		$amount_to_pay=$res['package_price'];		
		$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';		
								
		try {
		   $mp = new MP($admin_mercado_id, $admin_mercado_key);	
		   
		   $reference=Yii::app()->functions->generateRandomKey();
		   $_SESSION['mcd_ref']=$reference;		   
		   $preference_data = array(
				"items" => array(
					array(
					"title" => $res['package_name'],
					"currency_id" => Yii::app()->functions->adminCurrencyCode(),
					"category_id" => "services",
					"quantity" => 1,
					"unit_price" => (float)$amount_to_pay
					)
				  ),
				"back_urls" => array(
				"success" =>Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3b/token/$my_token/gateway/mcd/status/success".$extra_params,
				"failure" =>Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3b/token/$my_token/gateway/mcd/status/failure".$extra_params,
				"pending" =>Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3b/token/$my_token/gateway/mcd/status/pending".$extra_params,
			    ),
			    "auto_return"=>"approved",
			    "external_reference" => $reference,
		    );   		    
		    //dump($preference_data);
		    $preference = $mp->create_preference($preference_data);       
		    ?>
		    <div class="page-right-sidebar payment-option-page">
		    <div class="main">
		    <h2><?php echo Yii::t("default","Pay using Mercadopago")?></h2>
		    <?php if ($paymode=="Sandbox"):?>
		    <a href="<?php echo $preference["response"]["sandbox_init_point"];; ?>" name="MP-Checkout" class="lightblue-M-Ov-ArOn">Pay</a>
		    <?php else :?>
		    <a href="<?php echo $preference["response"]["init_point"]; ?>" name="MP-Checkout" class="lightblue-M-Ov-ArOn">
		    <?php echo Yii::t("default","Pay")?>
		    </a>
		    <?php endif;?>
		    <script type="text/javascript" src="http://mp-tools.mlstatic.com/buttons/render.js"></script>
		    <?php
		    
		    ?>
		      <div class="clear"></div>
		      
		      <div style="height:40px;"></div>
		       <a href="<?php echo Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3/token/$my_token"?>">
		       <i class="fa fa-chevron-circle-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
		    </div>
		    </div>
		    <?php		    		    
		   
		} catch (Exception $e) {			
			?>
			<div class="page-right-sidebar payment-option-page">
		    <div class="main">
		    <?php  echo "<p class=\"uk-text-danger\">".$e->getMessage()."</p>";?>
		    </div>
            </div>		    
			<?php
		}
	} else {
		?>
		    <div class="page-right-sidebar payment-option-page">
		    <div class="main">
		    <p class="uk-text-danger"><?php echo Yii::t("default","Sorry but we cannot find what your are looking for.");?></p>
		    </div>
            </div>		    
		<?php
	} 
}
?>