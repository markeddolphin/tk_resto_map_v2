<?php
$DbExt=new DbExt;  $amount_to_pay=0; $payment_description='';

$amount_to_pay=$data['price'];
if ( $data['promo_price']>0){
	$amount_to_pay=$data['promo_price'];
}	    		
$amount_to_pay=Yii::app()->functions->normalPrettyPrice($amount_to_pay);
$payment_description.=isset($data['title'])?$data['title']:'';	

$payment_ref="vog-".FunctionsV3::lastID('sms_package_trans');

if(!$credentials){
	$error=t("Invalid voguepay credentials");
}

if(isset($_GET['error'])){
	if(isset($_GET['message'])){
		$error=t($_GET['message']);
	} else {		
		$error=t("Payment Failed");
	}
}
?>

<div class="page-right-sidebar payment-option-page">
  <div class="main">  
 
  
   <h3><?php echo t("Pay using")." ".t("voguepay")?> </h3>
  
   <?php if ( !empty($error)):?>
     <p class="uk-text-danger"><?php echo $error;?></p>  
     
     <?php else :?>
     
     
    <form method='POST' action='https://voguepay.com/pay/'>
    
    <div class="uk-form-row" style="margin-bottom:20px;">           
      <label class="uk-form-label"><?php echo t("Amount")?>:</label>       
      <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
    </div>             
       
	  <?php	
	  echo CHtml::hiddenField('v_merchant_id',$credentials['merchant_id']);
	  echo CHtml::hiddenField('merchant_ref',$payment_ref);
	  echo CHtml::hiddenField('memo',stripslashes($payment_description));
	  echo CHtml::hiddenField('total', number_format($amount_to_pay,2,'.','') );
	  echo CHtml::hiddenField('notify_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/merchant/vognotify',array(
	      'payment_ref'=>$payment_ref,
	      'package_id'=>$package_id,
	      'merchant_id'=>$merchant_id
	  )));	  	  
	  echo CHtml::hiddenField('fail_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/merchant/voginit',array(
	   'type'=>"purchaseSMScredit",
	   'package_id'=>$package_id,
	   'error'=>1
	  )));
	  echo CHtml::hiddenField('success_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/merchant/vogsuccess',array(
	     'payment_ref'=>$payment_ref,
	     'package_id'=>$package_id,
	  )));
	  echo CHtml::hiddenField('cur', Yii::app()->functions->adminCurrencyCode() );	  
	  ?>
	   <input type='image' src='//voguepay.com/images/buttons/checkout_blue.png' alt='Submit' />
	 </form>

   <?php endif;?>	  
  
  </div>
</div>