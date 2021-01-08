<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));
//dump($credentials);
?>
<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Pay using")." ".t("voguepay")?></h1>
  <div class="box-grey rounded">	     
  <?php if ( !empty($error)):?>
  <p class="text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
          
  	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>
	
	<div class="row top10">
	<div class="col-md-12">
	
	 <form method='POST' action='https://voguepay.com/pay/'>
	  <?php 	  
	  echo CHtml::hiddenField('v_merchant_id',$credentials['merchant_id']);
	  echo CHtml::hiddenField('merchant_ref',$data['order_id']);
	  echo CHtml::hiddenField('memo',stripslashes($payment_description));
	  echo CHtml::hiddenField('total', number_format($amount_to_pay,2,'.','') );
	  echo CHtml::hiddenField('notify_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/store/vognotify',array(
	    'id'=>$data['order_id']
	  )));
	  	  
	  echo CHtml::hiddenField('fail_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/store/voginit',array(
	    'id'=>$_GET['id'],	    
	    'failed'=>1
	  )));
	  echo CHtml::hiddenField('success_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/store/vogsuccess',array(
	  	    'xid'=>$data['order_id']
	  	  )));
	  	  	  
	  echo CHtml::hiddenField('cur', Yii::app()->functions->adminCurrencyCode() );	  
	  ?>
	  
	   <input type='image' src='//voguepay.com/images/buttons/checkout_blue.png' alt='Submit' />
	 </form>
    
    </div>
    </div>
	
  <?php endif;?>
    
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
  
    </div>
   </div>
  </div>
 </div>
</div>