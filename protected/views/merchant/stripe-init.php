<?php
require_once('stripe/lib/Stripe.php');

$merchant_name=Yii::app()->functions->getOptionAdmin('website_title');
 if (empty($name)){		 	
 	$merchant_name=Yii::app()->name;
 }
		 
$step2=false;
$amount_to_pay=0;
$payment_description='';

$secret_key='';
$publishable_key='';

$my_token=isset($_GET['token'])?$_GET['token']:'';
$transaction_type=isset($_GET['type'])?$_GET['type']:'';

$payment_code=Yii::app()->functions->paymentCode("stripe");
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
	
	$mode=Yii::app()->functions->getOptionAdmin('admin_stripe_mode');   				
	$mode=strtolower($mode);	
	
	if ( $mode=="sandbox"){
		$secret_key=Yii::app()->functions->getOptionAdmin('admin_sanbox_stripe_secret_key');   
		$publishable_key=Yii::app()->functions->getOptionAdmin('admin_sandbox_stripe_pub_key');   
	} elseif ($mode=="live"){
		$secret_key=Yii::app()->functions->getOptionAdmin('admin_live_stripe_secret_key');   
		$publishable_key=Yii::app()->functions->getOptionAdmin('admin_live_stripe_pub_key');   
	}	
	if ( !empty($mode) && !empty($secret_key) && !empty($publishable_key) ){
				
		$amount_to_pay=$res['price'];
		if ( $res['promo_price']>0){
			$amount_to_pay=$res['promo_price'];
		}	    		
				
		$amount_to_pay=is_numeric($amount_to_pay)?unPrettyPrice($amount_to_pay*100):'';	
		$amount_to_pay=Yii::app()->functions->normalPrettyPrice2($amount_to_pay);
			
		$payment_description.=isset($res['title'])?$res['title']:'';	
				
		$stripe = array(
	     "secret_key"      => $secret_key,
	     "publishable_key" => $publishable_key
	    );
	    Stripe::setApiKey($stripe['secret_key']);
    
	} else $error=Yii::t("default","Stripe payment is not properly configured on admin portal.");
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");

if (isset($_POST)){
	if (is_array($_POST) && count($_POST)>=1){		
		$step2=true;
		$token=isset($_POST['stripeToken'])?$_POST['stripeToken']:'';
				
		try {
			$customer = Stripe_Customer::create(array(
		      'email' => isset($_POST['stripeEmail'])?$_POST['stripeEmail']:'',
		      'card'  => $token
		    ));
		    	           
	        $charge = Stripe_Charge::create(array(
	          'customer' => $customer->id,
	          'amount'   => $amount_to_pay,
	          'currency' => Yii::app()->functions->adminCurrencyCode()
	        ));	        
	        	        
	        $chargeArray = $charge->__toArray(true);            
	        $db_ext=new DbExt;
	        	        
	        $params=array(
			  'merchant_id'=>Yii::app()->functions->getMerchantID(),
			  'sms_package_id'=>$package_id,
			  'payment_type'=>$payment_code,
			  'package_price'=>$amount_to_pay/100,
			  'sms_limit'=>isset($res['sms_limit'])?$res['sms_limit']:'',
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'payment_gateway_response'=>json_encode($chargeArray),
			  'status'=>"paid"
			);	    	
				
			if ( $db_ext->insertData("{{sms_package_trans}}",$params)){				
				header('Location: '.Yii::app()->request->baseUrl."/merchant/smsReceipt/id/".Yii::app()->db->getLastInsertID());
			} else $error=Yii::t("default","ERROR: Cannot insert record.");	
	        	        	        	        
	    } catch (Exception $e)   {
	    	$error=$e;
	    }    
	}
}
?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
  <?php if ( $step2==TRUE):?>
  
  <?php else :?>
  <h2><?php echo Yii::t("default","Pay using Stripe Payment")?></h2>  
  <?php echo CHtml::beginForm( Yii::app()->request->baseUrl."/merchant/stripeInit/?type=".$transaction_type."&package_id=".$package_id , 'post');?>
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-name="<?php echo ucwords($payment_description);?>"
          data-amount="<?php echo $amount_to_pay;?>" 
          data-currency="<?php echo Yii::app()->functions->adminCurrencyCode();?>"
          data-description="<?php echo ucwords($payment_description);?>">
  </script>
  <?php echo CHtml::endForm() ; ?>
  <?php endif;?>
  
  <?php endif;?>
  </div>
</div>