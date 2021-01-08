<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  
  <?php
  /*dump($amount_to_pay);
  dump($payment_description);
  dump($credentials);
  dump($order_id);*/
  ?>
  
  <h1><?php echo t("Pi Pay")?></h1>
  <div class="box-grey rounded">	     
  
  <?php if (isset($_GET['errormsg'])):?>
  <p class="text-danger"><?php echo $_GET['errormsg']?></p>
  <?php endif;?>
  
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
    echo CHtml::hiddenField('payerEmail', $client_email ); 
    echo CHtml::hiddenField('orderDate', date("c") );     
    echo CHtml::hiddenField('payMethod', Pipay::payMethod() ); 
    echo CHtml::hiddenField('trType', Pipay::trType() ); 
    echo CHtml::hiddenField('var1', $order_id); 
    
    $digest = $credentials['pipay_merchant_id'];
    $digest.=$payment_ref;
    $digest.=$amount_to_pay;
    //dump($digest);
    $digest=md5($digest);    
    //dump($digest);
    
    echo CHtml::hiddenField('confirmURL', websiteUrl()."/pipayconfirm/?order_id=".$order_id."&transtype=order" ); 
    echo CHtml::hiddenField('cancelURL', websiteUrl()."/paymentoption" ); 
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
        <input type="submit" name="submit" value="<?php  echo t("Make Payment")?>" class="green-button medium inline block">
      </div>
    </div> <!--row-->
    
  </form>
  
  <div class="top25">
    <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
  </div>
   
  </div> <!--box-grey-->
  
  </div>
  </div>
</div>
</div>