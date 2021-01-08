<?php
$db_ext=new DbExt;
$payment_code=Yii::app()->functions->paymentCode("sisow");

$error='';
$success='';
$amount_to_pay=0;
$payment_description='';
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{sms_package_trans}}');
$data_get=$_GET;

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
	$amount_to_pay=$res['price'];
	if ( $res['promo_price']>0){
		$amount_to_pay=$res['promo_price'];
	}	    										
	$amount_to_pay=is_numeric($amount_to_pay)?normalPrettyPrice($amount_to_pay):'';
	$amount_to_pay=unPrettyPrice($amount_to_pay);	
	$payment_description.=isset($res['title'])?$res['title']:'';	
	/*dump($amount_to_pay);
	dump($payment_description);*/
	
	$mtid=Yii::app()->functions->getOptionAdmin('admin_sanbox_sisow_secret_key');
    $mtkey=Yii::app()->functions->getOptionAdmin('admin_sandbox_sisow_pub_key');
    $mtshopid=Yii::app()->functions->getOptionAdmin('admin_sandbox_sisow_shopid');
    $mode=Yii::app()->functions->getOptionAdmin('admin_sisow_mode');
    
    /*dump($mtid);dump($mtkey);
    dump($mtshopid);dump($mode);*/

    if ( empty($mtid) || empty($mtkey)){
		$error=Yii::t("default","This payment method is not properly configured");
	} else {
		$sisow = new Sisow($mtid, $mtkey,$mtshopid);
	}
	if ( empty($error)){
		if (isset($_POST["issuerid"])) {	
			//echo "proceess";
			
			$data=$_POST;			
			$return_url=Yii::app()->getBaseUrl(true)."/merchant/sisowinit/?type=purchaseSMScredit&package_id=".$package_id;
											
			$sisow->purchaseId = $payment_ref;
			$sisow->description = $payment_description;
			$sisow->amount = $amount_to_pay;
			$sisow->payment = $data['payment_method'];
			$sisow->issuerId = $data["issuerid"];
			$sisow->returnUrl = $return_url;
			$sisow->notifyUrl = $sisow->returnUrl;			
						
			if (($ex = $sisow->TransactionRequest()) < 0) {				
				$error=$sisow->errorCode." ".$sisow->errorMessage;
			} else header("Location: " . $sisow->issuerUrl);			
			
		} else if (isset($_GET["trxid"])) {
			
			if ($data_get['status']=="Success"){
				
				$params=array(
				  'merchant_id'=>Yii::app()->functions->getMerchantID(),
				  'sms_package_id'=>$package_id,
				  'payment_type'=>$payment_code,
				  'package_price'=>$amount_to_pay,
				  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
				  'date_created'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR'],
				  'payment_gateway_response'=>json_encode($data_get),
				  'status'=>"paid",
				  'payment_reference'=>isset($data_get['trxid'])?$data_get['trxid']:''
				);	    										
				if ( $db_ext->insertData("{{sms_package_trans}}",$params)){										
	                 header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
	            } else $error=Yii::t("default","ERROR: Cannot insert record.");				
				
			} else $error=Yii::t("default","Payment Failed"." ".$data_get['status']);
			
		} else {
			$testmode = $mode=="Sandbox"?true:false;
			$sisow->DirectoryRequest($select, true, $testmode);	
		}
	}	
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");

?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  
  <?php else :?>
  
  <h2><?php echo Yii::t("default","Pay using Sisow")?></h2>
   
  <form class="uk-form uk-form-horizontal forms"  method="POST" >
  <input type="hidden" id="action" name="action" value="sisowPayment">
    
  <?php echo CHtml::hiddenField('payment_ref',
  $payment_ref
  ,array(
  'class'=>'uk-form-width-large'  
  ))?>  
  <?php echo CHtml::hiddenField('description',
  $payment_description
  ,array(
  'class'=>'uk-form-width-large'  
  ))?>
   
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Amount")?></label>
  <?php echo CHtml::textField('amount',
  $amount_to_pay
  ,array(
  'class'=>'uk-form-width-large',
  'disabled'=>true
  ))?>
  </div>    
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Method")?></label>
   <select name="payment_method" class="uk-form-width-large" id="payment_method" >
    <option value="">iDEAL</option>
    <option value="sofort">DIRECTebanking</option>
    <option value="mistercash">MisterCash</option>
    <option value="webshop">WebShop GiftCard</option>
    <option value="podium">Podium Cadeaukaart</option>
  </select>
</div>

 <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank")?></label>
  <?php echo $select;?>
 </div>
 
<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>   
     
  </form>
  
  <?php endif;?>
  
  
  </div> <!--main-->
</div> <!--page-->