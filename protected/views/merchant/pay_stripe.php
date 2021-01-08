<?php
require_once('stripe/lib/Stripe.php');

/*dump($package_id);
dump($price);
dump($description);*/

$merchant_name=Yii::app()->functions->getOptionAdmin('website_title');
 if (empty($name)){		 	
 	$merchant_name=Yii::app()->name;
 }
		 
$step2=false;
$amount_to_pay=$price;
$payment_description=$description;

$secret_key='';
$publishable_key='';

$payment_code=Yii::app()->functions->paymentCode("stripe");

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
		
	$amount_to_pay=is_numeric($amount_to_pay)?unPrettyPrice($amount_to_pay*100):'';	
	$amount_to_pay=Yii::app()->functions->normalPrettyPrice2($amount_to_pay);
					
	$stripe = array(
     "secret_key"      => $secret_key,
     "publishable_key" => $publishable_key
    );
    Stripe::setApiKey($stripe['secret_key']);
	
} else $error=Yii::t("default","Stripe payment is not properly configured on admin portal.");

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
	        $FunctionsK=new FunctionsK;
	        	        	        
	        $info=$FunctionsK->getFaxPackagesById($package_id);
	        	        
	        $params=array(
		      'merchant_id'=>Yii::app()->functions->getMerchantID(),
			  'fax_package_id'=>$package_id,
			  'payment_type'=>$payment_code,
			  'package_price'=>$price,
			  'fax_limit'=>$info['fax_limit'],
			  'date_created'=>FunctionsV3::dateNow(),
			  'ip_address'=>$_SERVER['REMOTE_ADDR'],
			  'payment_gateway_response'=>json_encode($chargeArray),
			  'status'=>"paid"
			);	
						
			if ( $db_ext->insertData("{{fax_package_trans}}",$params)){				
			   $last_id = Yii::app()->db->getLastInsertID();
			   $merchantinfo=Yii::app()->functions->getMerchantInfo();
			   $FunctionsK->faxSendNotification((array)$merchantinfo[0],
               $package_id,
               "Stripe",
               $price);
				
                header('Location: '.Yii::app()->request->baseUrl."/merchant/$redirect/id/".$last_id);
                
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
    
  <?php echo CHtml::beginForm( $_SERVER['REQUEST_URI'] , 'post');?>

  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-name="<?php echo ucwords($merchant_name);?>"
          data-amount="<?php echo $amount_to_pay;?>" 
          data-currency="<?php echo Yii::app()->functions->adminCurrencyCode();?>"
          data-description="<?php echo ucwords($payment_description);?>">
  </script>
  <?php echo CHtml::endForm() ; ?>
  <?php endif;?>
  
  <?php endif;?>
  </div>
</div>