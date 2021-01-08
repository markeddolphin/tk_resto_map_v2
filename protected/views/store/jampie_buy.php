<?php
$error = '';
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

   <h1><?php echo t("Jampie Pay")?></h1>
   <div class="box-grey rounded">
   
          
  	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>
	
	<form action="http://jampie.com/epay2/checkout/" method="post">
	  <?php 	  
	  echo CHtml::hiddenField('business', isset($credentials['business_email'])?$credentials['business_email']:'' );
	  echo CHtml::hiddenField('type','buy');
	  echo CHtml::hiddenField('item_name',$payment_description);
	  echo CHtml::hiddenField('item_number',$ref);
	  echo CHtml::hiddenField('amount',unPrettyPrice($amount_to_pay));
	  echo CHtml::hiddenField('shipping',1);
	  
	  
	 echo CHtml::hiddenField('return', websiteUrl()."/success_jampie/?xtrans=order&order_id=$order_id&");
	 echo CHtml::hiddenField('cancel_return',websiteUrl()."/paymentoption");
	  	  
	  echo CHtml::hiddenField('currency_code',$currency_code);
	  ?>
	  <input type="submit" value="<?php echo t("Pay Now")?>" class="top30 black-button inline medium">
	</form>
  
  </div> <!--box-grey rounded-->
  
  <div class="top25">
     <a href="<?php echo $back_url?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo t("Click here to change payment option")?></a>
  </div>
  	  
  </div>
  </div>
  </div>
</div>	  