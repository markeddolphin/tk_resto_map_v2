<?php
$error=''; $payment_description='';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
$back_url=Yii::app()->createUrl('merchant/purchasesms');

$last_id = FunctionsV3::lastID("sms_package_trans");
$payment_ref = $last_id."-".Yii::app()->functions->generateRandomKey(6);    

if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
	$amount_to_pay=$res['price'];
	if ( $res['promo_price']>0){
		$amount_to_pay=$res['promo_price'];
	}	    										
	$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';	
	$payment_description.=isset($res['title'])?$res['title']:'';		
		
	$credentials = Pipay::getAdminCredentials();
    if(!$credentials){
    	$error=t("Credentials not valid");
    }     
    
    /*dump($payment_description);
	dump($amount_to_pay);
	dump($credentials);*/	
	
} else $error=t("Failed. Cannot process payment");  
?>


<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
   <?php if ($credentials['mode']=="sandbox"):?>
			   <form method="post" action="https://onlinepayment-test.pipay.com/starttransaction">
			   <?php else :?>
			   <form method="post" action="https://onlinepayment.pipay.com/starttransaction">
			   <?php endif;?>
			  
			    <?php       
			    echo CHtml::hiddenField('mid', $credentials['pipay_merchant_id']);
			    echo CHtml::hiddenField('did', $credentials['pipay_device_id']);
			    echo CHtml::hiddenField('sid', $credentials['pipay_store_id']);    
			    
			    echo CHtml::hiddenField('lang', Pipay::language() );    
			    echo CHtml::hiddenField('orderid', $payment_ref );     
			    echo CHtml::hiddenField('orderDesc', $payment_description ); 
			    echo CHtml::hiddenField('orderAmount', $amount_to_pay ); 
			    echo CHtml::hiddenField('currency', yii::app()->functions->adminCurrencyCode() );    
			    echo CHtml::hiddenField('payerEmail', isset($data['contact_email'])?$data['contact_email']:'' ); 
			    echo CHtml::hiddenField('orderDate', date("c") );     
			    echo CHtml::hiddenField('payMethod', Pipay::payMethod() ); 
			    echo CHtml::hiddenField('trType', Pipay::trType() ); 
			    echo CHtml::hiddenField('var1', $last_id); 
			    
			    $digest = $credentials['pipay_merchant_id'];
			    $digest.=$payment_ref;
			    $digest.=$amount_to_pay;
			    //dump($digest);
			    $digest=md5($digest);    
			    //dump($digest);
			    
			    echo CHtml::hiddenField('confirmURL', websiteUrl()."/pipayconfirm/?package_id=".$package_id."&transtype=sms" ); 
			    echo CHtml::hiddenField('cancelURL', Yii::app()->createUrl('merchant/purchasesms',array(
			      'package_id'=>$package_id,			      
			    ))); 
			    echo CHtml::hiddenField('digest', $digest ); 
			    ?>
			    
			    <div class="row top10">
				  <div class="col-md-3"><?php echo t("Amount")?></div>
				  <div class="col-md-8">
				    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
				  </div>
				</div>
			    
				<div class="row top10">
				  <div class="col-md-6">
			        <input type="submit" name="submit" value="<?php  echo t("Make Payment")?>" class="uk-button uk-form-width-medium uk-button-success">
			      </div>
			    </div> <!--row-->
			    
			  </form>

  
  <?php endif;?>      
  <div style="height:10px;"></div>
  <a href="<?php echo $back_url;?>"><?php echo Yii::t("default","Go back")?></a>
  
  </div>
</div>