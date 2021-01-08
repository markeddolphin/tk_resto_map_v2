<?php
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$amount_to_pay=0; $amount = 0;
$payment_description=Yii::t("default",'Membership Package - ');


$data=Yii::app()->functions->getMerchantByToken($my_token);
$merchant_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{package_trans}}');
$email_address = $data['contact_email'];
$merchant_id = $data['merchant_id'];

if ($data){
	$amount_to_pay=$data['package_price'];
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
	    
	    $amount_to_pay = $data['package_price'];
	    $amount=str_replace(",",'',$amount_to_pay)*100;
	}
}

$error='';

try {
	
	$params = array(
	  'amount'=>$amount,
	  'email'=>$email_address,
	  'reference'=>$merchant_ref,
	  'callback_url'=>websiteUrl()."/paystack_success"
	);		

	$db = new DbExt();
	$params_logs = array(
	 'merchant_id'=>$merchant_id,
	 'reference_number'=>$merchant_ref,
	 'token'=>$my_token,
	 'params1'=>$data['package_id'],
	 'date_created'=>FunctionsV3::dateNow(),
	 'ip_address'=>$_SERVER['REMOTE_ADDR'],	 
	);
	
	if (isset($_GET['renew'])) {
		if($_GET['renew']==1){
			$params_logs['transaction_type']='reg_renew';			
		}
	}
		
	if($db->insertData("{{paystack_logs}}",$params_logs)){
		$resp = PayStackWrapper::initPayment($merchant_ref,$params);
		header("Location: $resp");
	    Yii::app()->end();    
	} else $error = t("cannot insert records");
	
} catch (Exception $e) {
	$error = $e->getMessage();	
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

?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
	  <div class="inner">
	    <h1><?php echo t("Pay using")." ".t("paystack")?></h1>
	    <div class="box-grey rounded">	     
	       <?php if ( !empty($error)):?>
	         <p class="text-danger"><?php echo $error;?></p>  
	       <?php endif;?>
	    </div>
	    
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
  