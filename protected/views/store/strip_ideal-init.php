<?php
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');

$data=Yii::app()->functions->getMerchantByToken($my_token);

if ($data){
	$amount_to_pay=normalPrettyPrice($data['package_price']);
	$amount=str_replace(",",'',$amount_to_pay)*100;
	$payment_description.=isset($data['package_name'])?$data['package_name']:'';		
}

if (isset($_GET['renew'])) {
	if ($new_info=Yii::app()->functions->getPackagesById($package_id)){		    
	    $data['package_price']=$new_info['price'];
	    if ( $new_info['promo_price']>0){
		    $data['package_price']=$new_info['promo_price'];
	    }			
	    $data['package_name']=$new_info['title'];
	    $data['package_id']=$package_id;
	    
	    $amount_to_pay = unPrettyPrice($data['package_price']);	    
	}
}

$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("step 3 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>3,
   'show_bar'=>true
));

$publishable_key='';
$amount_to_pay=normalPrettyPrice($amount_to_pay);
$payment_description = addslashes($payment_description);
$stripe_amount = $amount_to_pay*100;
$stripe_currency = adminCurrencyCode();
$stripe_client_name = $data['restaurant_name'];
$stripe_return_url = websiteUrl()."/strip_ideal?trans_type=merchantreg&ref_token=".$my_token;
if (isset($_GET['renew'])){
	$stripe_return_url.="&package_id=".$package_id;
	$stripe_return_url.="&renew=1";
}

if ($credentials = StripeIdeal::getAdminCredentials()){	
	$publishable_key = $credentials['publishable_key'];
} else $error = t("Stripe settings is not properly configured");

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
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Paymill")?></h1>
  <div class="box-grey rounded">	     
  <?php if ( !empty($error)):?>
  <p class="text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
   <p class="payment-errors text-danger"></p>
          
  	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>
	
	<div class="row top10">
	<div class="col-md-12">
	
	 <form id="forms_stripe_ideal" class="forms_stripe_ideal"  method="POST" >
	 
	<div class="row top10">
	  <div class="col-md-3">&nbsp;</div>
	  <div class="col-md-8">
	  <input type="submit" id="stripe_ideal_submit" value="<?php echo t("Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>
	
	</form>
	
    </div>
    </div>
	
  <?php endif;?>
    
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
  
    </div>
   </div>
  </div>
 </div>
</div>