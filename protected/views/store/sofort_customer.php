<?php
namespace Sofort\SofortLib;

/*dump($amount_to_pay);
dump($payment_description);
dump($order_id);
dump($ref);
dump($currency_code);
dump($credentials);
*/

$error=''; $transactionId=''; $paymentUrl='';

spl_autoload_unregister(array('YiiBase','autoload'));
require "sofortlib/vendor/autoload.php";
spl_autoload_register(array('YiiBase','autoload'));

$Sofortueberweisung = new Sofortueberweisung( $credentials['config_key'] );

//$Sofortueberweisung->setAmount(1);
$Sofortueberweisung->setAmount($amount_to_pay);
$Sofortueberweisung->setCurrencyCode($currency_code);
if(isset($credentials['config_lang'])){
   if(!empty($credentials['config_lang'])){   	  
      $Sofortueberweisung->setLanguageCode($credentials['config_lang']);	
   }
}
$Sofortueberweisung->setReason($payment_description,$ref);
$Sofortueberweisung->setSuccessUrl( websiteUrl()."/sofort_success?id=".$order_id , true); // i.e. http://my.shop/order/success
$Sofortueberweisung->setAbortUrl( websiteUrl()."/confirmorder" );
$Sofortueberweisung->setNotificationUrl( websiteUrl()."/sofort_notify?id=".$order_id );

$Sofortueberweisung->sendRequest();
if($Sofortueberweisung->isError()) {
	$error =  "failed:".$Sofortueberweisung->getError();
} else {
	$transactionId = $Sofortueberweisung->getTransactionId();
	$paymentUrl = $Sofortueberweisung->getPaymentUrl();	
}

if (!empty($error)){
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

	  <p class="text-danger"><?php echo $error?></p>
	  
	  <div class="top25">
	     <a href="<?php echo $back_url?>">
	     <i class="ion-ios-arrow-thin-left"></i> <?php echo t("Click here to change payment option")?></a>
	  </div>
	  	  
	  </div>
	  </div>
	  </div>
    </div>	  
	<?php	
} else {
	$url = websiteUrl()."/sofort_update_order?order_id=".urlencode($order_id)."&transid=".urlencode($transactionId);
	@file_get_contents($url);	
	header('Location: '.$paymentUrl);
	die();
}