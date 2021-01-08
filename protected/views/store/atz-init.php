<?php
require 'authorize-sdk/vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

$error='';
$db_ext=new DbExt;
$data_get=$_GET;
$data_post=$_POST;

$amount_to_pay=0;

$my_token=isset($_GET['token'])?$_GET['token']:'';
$back_url=Yii::app()->request->baseUrl."/store/merchantSignup/Do/step3/token/".$my_token;

$payment_description='';
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{package_trans}}');

$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	


$mode_autho=Yii::app()->functions->getOptionAdmin('admin_mode_autho');
$autho_api_id=Yii::app()->functions->getOptionAdmin('admin_autho_api_id');
$autho_key=Yii::app()->functions->getOptionAdmin('admin_autho_key');

if ( empty($mode_autho) && empty($autho_api_id) && empty($autho_key)){
	$error=t("Authorize.net is not properly configured");
}

$extra_params='';
if (isset($_GET['renew'])){
	$extra_params="/renew/1/package_id/".$package_id;
}

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
		
	$merchant_id=$res['merchant_id'];
	$payment_description="Membership Package - ".$res['package_name'];
	$amount_to_pay = number_format($res['package_price'],2,'.','');
	
	if (isset($_POST['x_card_num'])){
		
		$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($autho_api_id);
        $merchantAuthentication->setTransactionKey($autho_key);
           
        $card_number = str_replace(" ",'',$_POST['x_card_num']);
        $card_number = trim($card_number);        
        
        $creditCard = new AnetAPI\CreditCardType();    
        $creditCard->setCardNumber( $card_number );
        $creditCard->setExpirationDate( $_POST['expiration_month']."-".$_POST['expiration_yr'] );
        $creditCard->setCardCode( $_POST['cvv'] );
       
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
       
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($payment_ref);
        $order->setDescription($payment_description);
              
	    $customerAddress = new AnetAPI\CustomerAddressType();
	    $customerAddress->setFirstName($_POST['x_first_name']);
	    $customerAddress->setLastName($_POST['x_last_name']);	    
	    $customerAddress->setAddress($_POST['x_address']);
	    $customerAddress->setCity($_POST['x_city']);
	    $customerAddress->setState($_POST['x_state']);
	    $customerAddress->setZip($_POST['x_zip']);
	    $customerAddress->setCountry($_POST['x_country']);
	    		    
	    /*if ($client_info = Yii::app()->functions->getClientInfo($client_id)){		    	
		    $customerData = new AnetAPI\CustomerDataType();
            $customerData->setType("individual");
            $customerData->setId($client_id);
            $customerData->setEmail($client_info['email_address']);
	    }*/
	    
	    $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");
        
        $transactionRequestType = new AnetAPI\TransactionRequestType();
	    $transactionRequestType->setTransactionType("authCaptureTransaction");
	    $transactionRequestType->setAmount($amount_to_pay);
	    $transactionRequestType->setOrder($order);
	    $transactionRequestType->setPayment($paymentOne);
	    $transactionRequestType->setBillTo($customerAddress);
        //$transactionRequestType->setCustomer($customerData);
	    $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
	    		    	    
	    $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($payment_ref);
        $request->setTransactionRequest($transactionRequestType);
        
        $controller = new AnetController\CreateTransactionController($request);
        if($mode_autho=="sandbox"){
           $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {            	
           $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }
        
        if ($response != null) {
        	
        	$resp_code = $response->getMessages()->getResultCode();
        	if ($response->getMessages()->getResultCode() == "Ok") {
        		$tresponse = $response->getTransactionResponse();
        		if ($tresponse != null && $tresponse->getMessages() != null) {
        			        		
        			$transaction_id = $tresponse->getTransId();
            	    $resp_description = $tresponse->getMessages()[0]->getDescription();
            	    
            	    $raw_response = array(
        			  'resp_code'=>$resp_code,
        			  'transaction_id'=>$transaction_id,
        			  'resp_description'=>$resp_description
        			);
            	    
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
				          'payment_type'=>Yii::app()->functions->paymentCode('authorize'),
				          'membership_expired'=>$membership_info['membership_expired'],
				          'date_created'=>FunctionsV3::dateNow(),
				          'ip_address'=>$_SERVER['REMOTE_ADDR'],
				          'PAYPALFULLRESPONSE'=>json_encode($raw_response),
				           'TRANSACTIONID'=>$transaction_id,
				           'TOKEN'=>$my_token
				        );
            	    } else {
	            	     $params=array(
				           'package_id'=>$res['package_id'],	          
				           'merchant_id'=>$res['merchant_id'],
				           'price'=>$res['package_price'],
				           'payment_type'=>Yii::app()->functions->paymentCode('authorize'),
				           'membership_expired'=>$res['membership_expired'],
				           'date_created'=>FunctionsV3::dateNow(),
				           'ip_address'=>$_SERVER['REMOTE_ADDR'],
				           'PAYPALFULLRESPONSE'=>json_encode($raw_response),
				           'TRANSACTIONID'=>$transaction_id,
				           'TOKEN'=>$my_token
				         );	    
            	    }   
            	    
            	    $db_ext->insertData("{{package_trans}}",$params);     		
            	    
            	    $db_ext->updateData("{{merchant}}",
								  array(
								    'payment_steps'=>3,
								    'membership_purchase_date'=>FunctionsV3::dateNow()
								  ),'merchant_id',$res['merchant_id']);
							
				   $okmsg=Yii::t("default","transaction was susccessfull");		  

				    if (isset($_GET['renew'])){
		             	$redirect= Yii::app()->createUrl('store/renewsuccesful');
		             } else {
		             	
		             	/*SEND EMAIL*/
		             	FunctionsV3::sendWelcomeEmailMerchant($res);
		             	FunctionsV3::sendMerchantActivation($res, isset($res['activation_key'])?$res['activation_key']:'' );
		             	$redirect=Yii::app()->createUrl('store/merchantSignup',array(
		                  'Do'=>"step4",
		                  'token'=>$my_token
		                ));
		             }
		             header("Location: $redirect");
				     Yii::app()->end();      
            			
        		} else {
        			$error = t("Transaction Failed")."<br/>";
            		$error.= $tresponse->getErrors()[0]->getErrorText();
        		}        	
        	} else {
        		$error = t("Transaction Failed")."<br/>";
        		$tresponse = $response->getTransactionResponse();
        		
        		if ($tresponse != null && $tresponse->getErrors() != null) {	                
	                $error.= $tresponse->getErrors()[0]->getErrorText() . "<br/>";
	            } else {	                
	                $error.=  $response->getMessages()->getMessage()[0]->getText() . "<br/>";
	            }
        	}        
        		        	
        } else $error = t("No response returned");
        
	}	
} else $error=Yii::t("default","Failed. Cannot process payment");  

$merchant_default_country=Yii::app()->functions->getOptionAdmin('admin_country_set');  

$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("step 3 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>3,
   'show_bar'=>true
));

?>


<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using Authorize.net")?></h1>
          <div class="box-grey rounded">	     
          
           <?php if ( !empty($error)):?>
              <p class="text-danger"><?php echo $error;?></p>  
           <?php endif;?>
           <?php //else :?>
               
               <?php echo CHtml::beginForm( '' , 'post', 
               array(
                'id'=>'forms-normal',
                'class'=>"forms"
               ));?>
                              
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("Amount")?></div>
				  <div class="col-md-8">
				    <?php echo CHtml::textField('amount',
					  number_format($amount_to_pay,2)
					  ,array(
					  'class'=>'grey-fields full-width',
					  'disabled'=>true
					  ))?>
				  </div>
				</div>
								

				<div class="row top10">
				  <div class="col-md-3"><?php echo t("Credit Card Number")?></div>
				  <div class="col-md-8">
				    <?php echo CHtml::textField('x_card_num',
				  isset($data_post['x_card_num'])?$data_post['x_card_num']:''
				  ,array(
				  'class'=>'grey-fields numeric_only full-width' ,
				  'data-validation'=>"required"  
				  ))?>
				  </div>
				</div>
				
				
                <div class="row top10">
				  <div class="col-md-3"><?php echo t("Exp. month")?></div>
				  <div class="col-md-8">
				      <?php echo CHtml::dropDownList('expiration_month',
				      isset($data_post['expiration_month'])?$data_post['expiration_month']:''
				      ,
				      Yii::app()->functions->ccExpirationMonth()
				      ,array(
				       'class'=>'grey-fields full-width',
				       'placeholder'=>Yii::t("default","Exp. month"),
				       'data-validation'=>"required"  
				      ))?>
				  </div>
				</div>
									
                <div class="row top10">
				  <div class="col-md-3"><?php echo t("Exp. year")?></div>
				  <div class="col-md-8">
				   <?php echo CHtml::dropDownList('expiration_yr',
				      isset($data_post['expiration_yr'])?$data_post['expiration_yr']:''
				      ,
				      Yii::app()->functions->ccExpirationYear()
				      ,array(
				       'class'=>'grey-fields full-width',
				       'placeholder'=>Yii::t("default","Exp. year") ,
				       'data-validation'=>"required"  
				      ))?>
				  </div>
				</div>
               				
               <div class="row top10">
				  <div class="col-md-3"><?php echo t("CCV")?></div>
				  <div class="col-md-8">
			      <?php echo CHtml::textField('cvv',
			      isset($data_post['cvv'])?$data_post['cvv']:''
			      ,array(
			       'class'=>'grey-fields full-width numeric_only',       
			       'data-validation'=>"required",
			       'maxlength'=>4
			      ))?>							 
				  </div>
				</div>
				

                <div class="row top10">
				  <div class="col-md-3"><?php echo t("First Name")?></div>
				  <div class="col-md-8">
				    <?php echo CHtml::textField('x_first_name',
  isset($data_post['x_first_name'])?$data_post['x_first_name']:''
  ,array(
  'class'=>'grey-fields full-width',
  'data-validation'=>"required"  
  ))?>
				  </div>
				</div>				
				
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("Last Name")?></div>
				  <div class="col-md-8">
  <?php echo CHtml::textField('x_last_name',
  isset($data_post['x_last_name'])?$data_post['x_last_name']:''
  ,array(
  'class'=>'grey-fields full-width',
  'data-validation'=>"required"  
  ))?>				  
				  </div>
				</div>				
				
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("Address")?></div>
				  <div class="col-md-8">
  <?php echo CHtml::textField('x_address',
  isset($data_post['x_address'])?$data_post['x_address']:''
  ,array(
  'class'=>'grey-fields full-width',
  'data-validation'=>"required"  
  ))?>
				  
				  </div>
				</div>				
				
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("City")?></div>
				  <div class="col-md-8">
  <?php echo CHtml::textField('x_city',
  isset($data_post['x_city'])?$data_post['x_city']:'' 
  ,array(
  'class'=>'grey-fields full-width',
  'data-validation'=>"required"   
  ))?>
				  
				  </div>
				</div>				
				
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("State")?></div>
				  <div class="col-md-8">
  <?php echo CHtml::textField('x_state',
  isset($data_post['x_state'])?$data_post['x_state']:''
  ,array(
  'class'=>'grey-fields full-width',
  'data-validation'=>"required"    
  ))?>
				  
				  </div>
				</div>				
				
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("Zip Code")?></div>
				  <div class="col-md-8">
  <?php echo CHtml::textField('x_zip',
  isset($data_post['x_zip'])?$data_post['x_zip']:''
  ,array(
  'class'=>'grey-fields full-width',
  'data-validation'=>"required"  
  ))?>
				  
				  </div>
				</div>				
				
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("Country")?></div>
				  <div class="col-md-8">
  <?php echo CHtml::dropDownList('x_country',
  isset($data_post['country_code'])?$data_post['country_code']:$merchant_default_country,
  (array)Yii::app()->functions->CountryListMerchant(),          
  array(
  'class'=>'grey-fields full-width',
  'data-validation'=>"required"
  ))?>
				  
				  </div>
				</div>			
				
				
               <div class="row top10">
				  <div class="col-md-3"></div>
				  <div class="col-md-8">
				  <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
				  </div>
				</div>	
				
               <?php echo CHtml::endForm() ; ?>
                             
           <?php //endif;?>
           
             <div class="top25">
			 <a href="<?php 
			 if (isset($_GET['renew'])) {		 
			    	echo Yii::app()->createUrl('store/merchantsignup',array(
				     'do'=>"step3",
				     'token'=>$my_token,
				     'renew'=>1,
				     'package_id'=>$package_id
				   ));
			    } else {
	 			    echo Yii::app()->createUrl('store/merchantsignup',array(
				      'do'=>"step3",
				      'token'=>$my_token));
			    }
			 ?>">
	         <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
	         </div>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->