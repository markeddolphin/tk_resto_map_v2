<?php
/*dump($order_id);
dump($amount_to_pay);
dump($amount);
dump($payment_description);*/

$publishable_key = $credentials['publishable_key'];
$stripe_amount = $total_amount_to_pay*100;
$payment_description = addslashes($payment_description);
$stripe_currency = adminCurrencyCode();
$stripe_client_name = addslashes(FunctionsV3::getClientName(true));
$stripe_return_url = websiteUrl()."/strip_ideal?trans_type=buy&order_id=".$order_id;

//dump($stripe_amount); die();

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile("https://js.stripe.com/v3/"); 			

$cs->registerScript(
  'stripe',
  "var stripe=Stripe('$publishable_key');",
   CClientScript::POS_HEAD
);
$cs->registerScript(
  'stripe_amount',
   "var stripe_amount='$stripe_amount';",
   CClientScript::POS_HEAD
);
$cs->registerScript(
  'stripe_descriptor',
   "var stripe_descriptor='$payment_description';",
   CClientScript::POS_HEAD
);
$cs->registerScript(
  'stripe_currency',
  "var stripe_currency='$stripe_currency';",
   CClientScript::POS_HEAD
);
$cs->registerScript(
  'stripe_client_name',
  "var stripe_client_name='$stripe_client_name';",
   CClientScript::POS_HEAD
);
$cs->registerScript(
  'stripe_return_url',
  "var stripe_return_url='$stripe_return_url';",
   CClientScript::POS_HEAD
);


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
  <h1><?php echo t("Ideal")?></h1>
  <div class="box-grey rounded">	     

   <p class="payment-errors text-danger"></p>
   
    <form id="forms_stripe_ideal" class="forms_stripe_ideal"  method="POST" onsubmit="return false;" >
      
    
   <div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('amount',
		  normalPrettyPrice($amount_to_pay)
		  ,array(
		  'class'=>'grey-fields full-width',
		  'disabled'=>true
		  ))?>
	  </div>
	</div>	
  
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
    
     <div class="row top10">
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	  <input type="submit" id="stripe_ideal_submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>	
	
	</form>
  
    </div>
   </div>
  </div>
 </div>
</div>