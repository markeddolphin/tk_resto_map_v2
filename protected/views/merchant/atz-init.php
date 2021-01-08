<?php
require 'authorize-sdk/vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

$db_ext=new DbExt;
$payment_code=Yii::app()->functions->paymentCode("authorize");

$error='';
$success='';
$amount_to_pay=0;
$payment_description='';
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{sms_package_trans}}');
$data_get=$_GET;

$data_post=$_POST;

$merchant_default_country=Yii::app()->functions->getOptionAdmin('merchant_default_country');  

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$mode_autho=Yii::app()->functions->getOptionAdmin('admin_mode_autho');
$autho_api_id=Yii::app()->functions->getOptionAdmin('admin_autho_api_id');
$autho_key=Yii::app()->functions->getOptionAdmin('admin_autho_key');

if ( empty($mode_autho) && empty($autho_api_id) && empty($autho_key)){
	$error=t("Authorize.net is not properly configured");
}

if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
	$amount_to_pay=$res['price'];
	if ( $res['promo_price']>0){
		$amount_to_pay=$res['promo_price'];
	}	    										
	$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';
	$amount_to_pay=unPrettyPrice($amount_to_pay);	
	$payment_description.=isset($res['title'])?$res['title']:'';	
		
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
       
       // Set the customer's Bill To address
	    $customerAddress = new AnetAPI\CustomerAddressType();
	    $customerAddress->setFirstName($_POST['x_first_name']);
	    $customerAddress->setLastName($_POST['x_last_name']);
	    //$customerAddress->setCompany("");
	    $customerAddress->setAddress($_POST['x_address']);
	    $customerAddress->setCity($_POST['x_city']);
	    $customerAddress->setState($_POST['x_state']);
	    $customerAddress->setZip($_POST['x_zip']);
	    $customerAddress->setCountry($_POST['x_country']);
	    		    
	    
	    
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
        			
        			$params=array(
					  'merchant_id'=>Yii::app()->functions->getMerchantID(),
					  'sms_package_id'=>$package_id,
					  'payment_type'=>$payment_code,
					  'package_price'=>$amount_to_pay,
					  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
					  'date_created'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR'],
					  'payment_gateway_response'=>json_encode($raw_response),
					  'status'=>"paid",
					  'payment_reference'=>$transaction_id
					);	    
					
					if ( $db_ext->insertData("{{sms_package_trans}}",$params)){										
                       header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
                       Yii::app()->end();
                    } else $error=Yii::t("default","ERROR: Cannot insert record.");
                            			
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
        } else $error = t("Missing payment credentials");            
	}		  
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");
?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  
  
  <!--<h2><?php echo Yii::t("default","Pay using Authorize.net")?></h2>-->
  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  <?php endif;?>
    
  <?php echo CHtml::beginForm( '' , 'post', 
array(
  'id'=>'forms-normal',
  'class'=>'uk-form uk-form-horizontal forms'
));?>
    
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Amount")?></label>
  <?php echo CHtml::textField('amount',
  $amount_to_pay
  ,array(
  'class'=>'uk-form-width-large',
  'disabled'=>true
  ))?>
  </div>    
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Credit Card Number")?></label>
  <?php echo CHtml::textField('x_card_num',
  isset($data_post['x_card_num'])?$data_post['x_card_num']:''
  ,array(
  'class'=>'uk-form-width-large numeric_only' ,
  'data-validation'=>"required"  
  ))?>
  </div>    
   
  <div class="uk-form-row">           
      <label class="uk-form-label"><?php echo Yii::t("default","Exp. month")?></label>       
      <?php echo CHtml::dropDownList('expiration_month',
      isset($data_post['expiration_month'])?$data_post['expiration_month']:''
      ,
      Yii::app()->functions->ccExpirationMonth()
      ,array(
       'class'=>'uk-form-width-large',
       'placeholder'=>Yii::t("default","Exp. month"),
       'data-validation'=>"required"  
      ))?>
   </div>             
   
   <div class="uk-form-row">                  
      <label class="uk-form-label"><?php echo Yii::t("default","Exp. year")?></label>       
      <?php echo CHtml::dropDownList('expiration_yr',
      isset($data_post['expiration_yr'])?$data_post['expiration_yr']:''
      ,
      Yii::app()->functions->ccExpirationYear()
      ,array(
       'class'=>'uk-form-width-large',
       'placeholder'=>Yii::t("default","Exp. year") ,
       'data-validation'=>"required"  
      ))?>
   </div>             
   
   <div class="uk-form-row">                  
      <label class="uk-form-label"><?php echo Yii::t("default","CCV")?></label>       
      <?php echo CHtml::textField('cvv',
      isset($data_post['cvv'])?$data_post['cvv']:''
      ,array(
       'class'=>'uk-form-width-large numeric_only',       
       'data-validation'=>"required",
       'maxlength'=>4
      ))?>
   </div>   
   
   
 <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","First Name")?></label>
  <?php echo CHtml::textField('x_first_name',
  isset($data_post['x_first_name'])?$data_post['x_first_name']:''
  ,array(
  'class'=>'uk-form-width-large ',
  'data-validation'=>"required"  
  ))?>
  </div>       
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Last Name")?></label>
  <?php echo CHtml::textField('x_last_name',
  isset($data_post['x_last_name'])?$data_post['x_last_name']:''
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"  
  ))?>
  </div>       
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Address")?></label>
  <?php echo CHtml::textField('x_address',
  isset($data_post['x_address'])?$data_post['x_address']:''
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"  
  ))?>
  </div>       
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","City")?></label>
  <?php echo CHtml::textField('x_city',
  isset($data_post['x_city'])?$data_post['x_city']:'' 
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"   
  ))?>
  </div>       
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","State")?></label>
  <?php echo CHtml::textField('x_state',
  isset($data_post['x_state'])?$data_post['x_state']:''
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"    
  ))?>
  </div>       
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Zip Code")?></label>
  <?php echo CHtml::textField('x_zip',
  isset($data_post['x_zip'])?$data_post['x_zip']:''
  ,array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"  
  ))?>
  </div>       
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Country")?></label>
  <?php echo CHtml::dropDownList('x_country',
  isset($data_post['country_code'])?$data_post['country_code']:$merchant_default_country,
  (array)Yii::app()->functions->CountryListMerchant(),          
  array(
  'class'=>'uk-form-width-large',
  'data-validation'=>"required"
  ))?>
  </div>       
  
  <div class="uk-form-row">
  <label class="uk-form-label"></label>
  <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="uk-button uk-form-width-medium uk-button-success">
  </div>   

  <?php echo CHtml::endForm() ; ?>
    
  
  </div>
</div>