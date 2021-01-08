<?php
$DbExt=new DbExt;  $amount_to_pay=0; $payment_description='';

$amount_to_pay=$data['price'];
if ( $data['promo_price']>0){
	$amount_to_pay=$data['promo_price'];
}	    		
$amount_to_pay=Yii::app()->functions->normalPrettyPrice($amount_to_pay);
$payment_description.=isset($data['title'])?$data['title']:'';	

if (isset($_POST)){
	if (is_array($_POST) && count($_POST)>=1){
	    require_once 'mpgClasses.php';
	   $data_post=$_POST;				
	   $payment_ref="SMS-" . Moneris::generatePaymentRef(). "-"  .Moneris::lastID('sms_package_trans');
	   
	   $txnArray=array(
	       'type'=>"purchase",
		   'order_id'=>$payment_ref,		   
	       'amount'=>$amount_to_pay,
		   'pan'=>$data_post['x_card_num'],
		   'expdate'=>substr($data_post['expiration_yr'],2,2).$data_post['expiration_month'],
		   'crypt_type'=>Moneris::cryptType(),
		   'dynamic_descriptor'=>$payment_description
	   );
	   
	   $cvdTemplate = array(
		    'cvd_indicator' => 1,
		    'cvd_value' => $data_post['x_cvv'] 
		);
		
	   //dump($txnArray);
	   if ( $credentials=Moneris::getCredentials()){
	   	    //dump($credentials);
	   	    $mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
	   	    
	   	    $mpgTxn = new mpgTransaction($txnArray);
	   	    
	   	    $mpgTxn->setCvdInfo($mpgCvdInfo);
	   	    
	    	$mpgRequest = new mpgRequest($mpgTxn);
	    	$mpgRequest->setProcCountryCode( $credentials['country_code'] );
	    	$mpgRequest->setTestMode( $credentials['mode'] );
	    		    	
	    	$mpgHttpPost  =new mpgHttpsPost(trim($credentials['store_id']), trim($credentials['token']) ,$mpgRequest);	    	
	    	$resp=$mpgHttpPost->getMpgResponse();

	    	$cvv_response = $resp->getCvdResultCode();
	    	if (!empty($cvv_response)){
	    		$cvv_response=str_replace("1","",$cvv_response);
	    	}    	
	    	
	    	//if ( $resp->getResponseCode()=="027"){
	    	if ( in_array($resp->getResponseCode(),Moneris::approvedResponsenCode() )){
	    		if ( $cvv_response=="M" || $cvv_response=="1M"){
	    		
		        	$full_response=json_encode($resp->responseData);
		        	
		        	$params=array(
					  'merchant_id'=>Yii::app()->functions->getMerchantID(),
					  'sms_package_id'=>$package_id,
					  'payment_type'=>Moneris::getPaymentCode(),
					  'package_price'=>$amount_to_pay,
					  'sms_limit'=>isset($data['sms_limit'])?$data['sms_limit']:'',
					  'date_created'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR'],
					  'payment_gateway_response'=>$full_response,
					  'status'=>"paid",
					  'payment_reference'=>$resp->getReferenceNum(),
					);	    
					
					if ( $DbExt->insertData("{{sms_package_trans}}",$params)){								    
					    $redirect_url=Yii::app()->createUrl('merchant/smsreceipt',array(
					      'id'=>Yii::app()->db->getLastInsertID()
					    ));
					    header("location: $redirect_url");
					    Yii::app()->end();
				    } else $error=Yii::t("default","ERROR: Cannot insert record.");	
	        	
			    } else $error= Moneris::CvvResult( $cvv_response );
	    	} else $error=$resp->getMessage();
	   } else $error=t("Credentials not yet set");
	}
}
?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
 
  
   <h3><?php echo t("Pay using moneris")?></h3>
  
   <?php if ( !empty($error)):?>
     <p class="uk-text-danger"><?php echo $error;?></p>  
   <?php endif;?>
  
   
    <form id="forms-normal" class="uk-form uk-form-horizontal forms"  method="POST" >
  
    <div class="uk-form-row">           
      <label class="uk-form-label"><?php echo t("Amount")?></label>       
      <?php echo CHtml::textField('amount',
	  isset($amount_to_pay)?$amount_to_pay:''
	  ,array(
	  'class'=>'grey-fields',
	  'disabled'=>true
	  ))?>
    </div>             
    
    <div class="uk-form-row">           
      <label class="uk-form-label"><?php echo t("Credit Card Number")?></label>       
       <?php echo CHtml::textField('x_card_num',
	  isset($data_post['x_card_num'])?$data_post['x_card_num']:''
	  ,array(
	  'class'=>'numeric_only uk-form-width-large' ,
	  'data-validation'=>"required",
	  'maxlength'=>16
	  ))?>
    </div>             
    
    <div class="uk-form-row">           
      <label class="uk-form-label"><?php echo t("Exp. month")?></label>       
       <?php echo CHtml::dropDownList('expiration_month',
      isset($data_post['expiration_month'])?$data_post['expiration_month']:''
      ,
      Yii::app()->functions->ccExpirationMonth()
      ,array(
       'class'=>'grey-fields uk-form-width-large',
       'placeholder'=>Yii::t("default","Exp. month"),
       'data-validation'=>"required"  
      ))?>
    </div>      
    
    <div class="uk-form-row">           
      <label class="uk-form-label"><?php echo t("Exp. year")?></label>       
       <?php echo CHtml::dropDownList('expiration_yr',
	      isset($data_post['expiration_yr'])?$data_post['expiration_yr']:''
	      ,
	      Yii::app()->functions->ccExpirationYear()
	      ,array(
	       'class'=>'grey-fields uk-form-width-large',
	       'placeholder'=>Yii::t("default","Exp. year") ,
	       'data-validation'=>"required"  
	      ))?>
    </div>       
    
    <div class="uk-form-row">           
      <label class="uk-form-label"><?php echo t("CVV")?></label>       
        <?php echo CHtml::textField('x_cvv',
			  isset($data_post['x_cvv'])?$data_post['x_cvv']:''
			  ,array(
			  'class'=>'grey-fields uk-form-width-large numeric_only' ,
			  'data-validation'=>"required",
			  'maxlength'=>4
			  ))?>
    </div>       
    
   <div class="uk-form-row">
     <label class="uk-form-label"></label>
     <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="uk-button uk-form-width-medium uk-button-success">
     
   </div>    
   
   <a href="<?php echo Yii::app()->createUrl('/merchant/purchasesms')?>"><?php echo t("Back")?></a>
   
   </form>
  
  </div>
</div>