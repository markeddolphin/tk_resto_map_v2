<?php
$db_ext=new DbExt;
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
$amount_to_pay=0;
$payment_description='';

$back_url=Yii::app()->request->baseUrl."/merchant/purchasesms";
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{sms_package_trans}}');

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
	$amount_to_pay=$res['price'];
	if ( $res['promo_price']>0){
		$amount_to_pay=$res['promo_price'];
	}	    										
	$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';	
	$payment_description.=isset($res['title'])?$res['title']:'';		
	
	/*dump($payment_description);
	dump($amount_to_pay);*/
	
} else $error=Yii::t("default","Failed. Cannot process payment");  
?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
  <?php require_once("payu.php")?>
  <?php 
  if ( $success==TRUE){  	  
  	  $payment_code=Yii::app()->functions->paymentCode("payumoney");
  	  $params=array(
		  'merchant_id'=>Yii::app()->functions->getMerchantID(),
		  'sms_package_id'=>$package_id,
		  'payment_type'=>$payment_code,
		  'package_price'=>$amount_to_pay,
		  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
		  'date_created'=>FunctionsV3::dateNow(),
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'payment_gateway_response'=>json_encode($_POST),
		  'status'=>"paid",
		  'payment_reference'=>$_POST['txnid']
		);	    						
		$db_ext->insertData("{{sms_package_trans}}",$params);
        header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
  }
  ?>
  
  <?php endif;?>      
  <div style="height:10px;"></div>
  <a href="<?php echo $back_url;?>"><?php echo Yii::t("default","Go back")?></a>
  
  </div>
</div>