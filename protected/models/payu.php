<?php
$error1='';
$action='';
$merchant_hash='';
$success=false;
$status='';

if (isset($is_merchant)){	
	$merchant_key=Yii::app()->functions->getOption('merchant_payu_key',$merchant_id);
	$merchant_salt=Yii::app()->functions->getOption('merchant_payu_salt',$merchant_id);
	$paymode=Yii::app()->functions->getOption('merchant_payu_mode',$merchant_id);
	//echo 'merchant';
	
	/*COMMISSION*/
	//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
		$merchant_key=Yii::app()->functions->getOptionAdmin('admin_payu_key');
	    $merchant_salt=Yii::app()->functions->getOptionAdmin('admin_payu_salt');
	    $paymode=Yii::app()->functions->getOptionAdmin('admin_payu_mode');
	}	
	
} else {
	//echo 'admin';
	$merchant_key=Yii::app()->functions->getOptionAdmin('admin_payu_key');
	$merchant_salt=Yii::app()->functions->getOptionAdmin('admin_payu_salt');
	$paymode=Yii::app()->functions->getOptionAdmin('admin_payu_mode');
}




/*dump($paymode);
dump($merchant_key);
dump($merchant_salt);*/

if ( $paymode=="Sandbox"){
    $PAYU_BASE_URL = "https://test.payu.in";
} else $PAYU_BASE_URL = "https://secure.payu.in/_payment";

$extra_params='';
if (isset($_GET['renew'])){
	$extra_params="/renew/1/package_id/".$package_id;
}

if (isset($is_merchant)){	
   $return_url=Yii::app()->getBaseUrl(true)."/store/payuinit/id/".$_GET['id'];
} else {
   $return_url=Yii::app()->getBaseUrl(true)."/store/merchantSignup/Do/step3b/token/$my_token/gateway/payu".$extra_params;
   if (isset($_GET['type'])){
   	  if ( $_GET['type']=="purchaseSMScredit"){
   	  	  $return_url=Yii::app()->getBaseUrl(true)."/merchant/payuinit/?type=purchaseSMScredit&package_id=".$package_id;
   	  }
   }   
}

$client_info = Yii::app()->functions->getClientInfo( Yii::app()->functions->getClientId());
if(is_array($client_info) && count($client_info)>=1 ){	
	if (!isset($_POST['email'])){
		$_POST['email']=$client_info['email_address'];
	}
	if (!isset($_POST['firstname'])){
		$_POST['firstname']=$client_info['first_name'];
	}
	if (!isset($_POST['phone'])){
		$_POST['phone']=$client_info['contact_phone'];
	}
}
//dump($return_url);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)){
	
	$data_post=$_POST;
	
	if (isset($_POST["status"])){
		
		$status=$_POST["status"];
		$firstname=$_POST["firstname"];
		$amount=$_POST["amount"];
		$txnid=$_POST["txnid"];
		$posted_hash=$_POST["hash"];
		$key=$_POST["key"];
		$productinfo=$_POST["productinfo"];
		$email=$_POST["email"];
						
		If (isset($data_post["additionalCharges"])) {
			$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
		} else {
			$retHashSeq = $merchant_salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
		}		
		$hash = hash("sha512", $retHashSeq);		
		if ($hash == $posted_hash) {
			if ( $status=="success"){				
				$success=true;
			} else $error1=Yii::t("default","Transaction failed."." ".$status);
		} else $error1=Yii::t("default","Invalid Transaction. Please try again");
		
	} else {
					
		$Validator=new Validator;
		$req=array(
		  'firstname'=>Yii::t("default","First name is required"),
		  'email'=>Yii::t("default","Email address is required"),
		  'phone'=>Yii::t("default","Phone is required"),
		);		
		$Validator->required($req,$data_post);
		if ($Validator->validate()){
			
			//$amount_to_pay=number_format($amount_to_pay,0);			
			$amount_to_pay=normalPrettyPrice($amount_to_pay);
			$hash_string ="$merchant_key|$payment_ref|$amount_to_pay|$payment_description|";
			$hash_string.=$data_post['firstname']."|";
			$hash_string.=$data_post['email']."|||||||||||";			
			
			$hash_string .= $merchant_salt;			
	
	
	        $merchant_hash = strtolower(hash('sha512', $hash_string));
	        $action = $PAYU_BASE_URL . '/_payment';
	        
	        /*dump($hash_string);
	        dump($action);
	        die();*/
	        	        
		} else $error1=$Validator->getErrorAsHTML();
	}
}
?>

<?php if ( !empty($error1)):?>
<div class="uk-alert uk-alert-danger"><?php echo $error1;?></div>  
<?php elseif ($success==TRUE):?>
<div class="uk-alert uk-alert-success"><?php echo Yii::t("default","Payment Successful")?></div>  
<?php echo CHtml::hiddenField('payu_status',$status)?>
<?php endif;?>

<!--<form  name="payuForm" id="payuForm" method="POST" action="<?php echo $action?>" class="uk-form uk-form-horizontal" >-->

<?php echo CHtml::beginForm( $action , 'post', 
  array( 
  'id'=>'payuForm',
  'name'=>"payuForm"
));?>
	   
<input type="hidden" name="key" value="<?php echo $merchant_key ?>" />
<input type="hidden" name="hash" id="hash" value="<?php echo $merchant_hash ?>"/>
<input type="hidden" name="txnid" value="<?php echo $payment_ref ?>" />

<?php echo CHtml::hiddenField('productinfo',$payment_description)?>
<?php echo CHtml::hiddenField('surl',$return_url)?>
<?php echo CHtml::hiddenField('furl',$return_url)?>
<?php echo CHtml::hiddenField('curl',$return_url)?>
<?php echo CHtml::hiddenField('service_provider','payu_paisa')?>
<?php echo CHtml::hiddenField('amount',$amount_to_pay)?>


<div class="row top10">
  <div class="col-md-3"><?php echo Yii::t("default","Amount to pay")?></div>
  <div class="col-md-8">
  <?php echo CHtml::textField('amounts',
$amount_to_pay
,array(
'class'=>'grey-fields full-width',
'disabled'=>true
))?>
  </div>
</div>

<div class="row top10">
  <div class="col-md-3"><?php echo t("First Name")?></div>
  <div class="col-md-8">
  <?php echo CHtml::textField('firstname',
isset($_POST['firstname'])?$_POST['firstname']:''
,array(
'class'=>'grey-fields full-width',
'data-validation'=>"required",
'required'=>true
))?>
  </div>
</div>


<div class="row top10">
  <div class="col-md-3"><?php echo t("Email address")?></div>
  <div class="col-md-8">
<?php echo CHtml::textField('email',
isset($_POST['email'])?$_POST['email']:''
,array(
'class'=>'grey-fields full-width',
'data-validation'=>"required",
'required'=>true
))?>  
  </div>
</div>

<div class="row top10">
  <div class="col-md-3"><?php echo t("Phone")?></div>
  <div class="col-md-8">
<?php echo CHtml::textField('phone',
isset($_POST['phone'])?str_replace("+","",$_POST['phone']):''
,array(
'class'=>'grey-fields full-width',
'data-validation'=>"required",
'required'=>true
))?>  
  </div>
</div>


<div class="row top10">
<div class="col-md-3"></div>
<div class="col-md-8">
<input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button medium inline">
</div>
</div>

      
<!--</form>-->
<?php echo CHtml::endForm() ; ?>