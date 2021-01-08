<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$cs = Yii::app()->getClientScript();
if(isset($_GET['debug'])){
   dump($credentials);
}

$params['customerId']= $credentials['customer_id'];
$params['shopId']= $credentials['shop_id'];
$params['amount']= normalPrettyPrice($amount_to_pay);
$params['currency']=adminCurrencyCode();
$params['orderDescription']=$payment_description;
//$params['customerStatement']='';
$params['orderReference']=$order_id_token;
$params['successUrl']= websiteUrl()."/wirecard_process?url_type=success&trans_type=buy&order_id=".$order_id_token;
$params['cancelUrl']= websiteUrl()."/paymentoption";
$params['failureUrl']= websiteUrl()."/paymentoption";
$params['pendingUrl']= websiteUrl()."/wirecard_process?url_type=pending&trans_type=buy&order_id=".$order_id_token;
$params['serviceUrl']= websiteUrl()."/contact";
$params['confirmUrl']= websiteUrl()."/wirecard_confirm?url_type=confirm&trans_type=buy&order_id=".$order_id_token;
$params['language']=$credentials['lang'];
$params['displayText']=$credentials['display_text'];
$params['imageUrl']="http://".$_SERVER['HTTP_HOST'].FunctionsV3::getDesktopLogo();
$params["requestFingerprintOrder"] = WireCard::getRequestFingerprintOrder($params);
$params["requestFingerprint"] = WireCard::getRequestFingerprint($params, $credentials["secret"]);
$params['paymenttype']="SELECT";

if(isset($_GET['debug'])){
   dump($params);
}
?>


<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Ipay Africa")?></h1>
  <div class="box-grey rounded">	     

   <p class="payment-errors text-danger"></p>
   
    <form id="frm_redirect_payment" class="frm_redirect_payment"  method="POST" action="https://checkout.wirecard.com/page/init.php" >
    <?php
    foreach ($params as $key => $val) {
    	echo CHtml::hiddenField($key,$val);
    }
    ?>
    
    <div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Description")?></div>
	  <div class="col-md-8">
	    <?php echo $payment_description?>
	  </div>
	</div>	

  
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
    
     <div class="row top10">
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	  <input type="submit" id="africa_submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>	
	
	</form>
  
    </div>
   </div>
  </div>
 </div>
</div>