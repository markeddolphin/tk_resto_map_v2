<?php
namespace Sofort\SofortLib;

$error='';

spl_autoload_unregister(array('YiiBase','autoload'));
require "sofortlib/vendor/autoload.php";
spl_autoload_register(array('YiiBase','autoload'));

$SofortLibTransactionData = new TransactionData( $credentials['config_key'] );
$SofortLibTransactionData->addTransaction($sofort_trans_id);
$SofortLibTransactionData->setApiVersion('2.0');

$SofortLibTransactionData->sendRequest();


$output = array();
$methods = array(
    'getAmount' => '',
    'getAmountRefunded' => '',
    'getCount' => '',
    'getPaymentMethod' => '',
    'getConsumerProtection' => '',
    'getStatus' => '',
    'getStatusReason' => '',
    'getStatusModifiedTime' => '',
    'getLanguageCode' => '',
    'getCurrency' => '',
    'getTransaction' => '',
    'getReason' => array(0,0),
    'getUserVariable' => 0,
    'getTime' => '',
    'getProjectId' => '',
    'getRecipientHolder' => '',
    'getRecipientAccountNumber' => '',
    'getRecipientBankCode' => '',
    'getRecipientCountryCode' => '',
    'getRecipientBankName' => '',
    'getRecipientBic' => '',
    'getRecipientIban' => '',
    'getSenderHolder' => '',
    'getSenderAccountNumber' => '',
    'getSenderBankCode' => '',
    'getSenderCountryCode' => '',
    'getSenderBankName' => '',
    'getSenderBic' => '',
    'getSenderIban' => '',
);

foreach($methods as $method => $params) {
	//dump($method);dump($params); die();
    if(count($params) == 2) {
        $output[] = $method . ': ' . $SofortLibTransactionData->$method($params[0], $params[1]);
    } else if($params !== '') {
        $output[] = $method . ': ' . $SofortLibTransactionData->$method($params);
    } else {
        $output[] = $method . ': ' . $SofortLibTransactionData->$method();
    }
}

if($SofortLibTransactionData->isError()) {
    $error =  $SofortLibTransactionData->getError();
} else {
	$status_accepeted = array('untraceable','received','pending');
	$status = $SofortLibTransactionData->getStatus();	
	if ( in_array($status,$status_accepeted)){		
		$this->renderPartial('/store/sofort_update_order',array(
		   'status'=>$status,
		   'order_id'=>$order_id,
		   'sofort_trans_id'=>$sofort_trans_id,
		));
	} else $error = t("Payment Failed")." :".$status;
}

if(empty($error)){
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
}