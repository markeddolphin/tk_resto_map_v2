<?php
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');


$data=Yii::app()->functions->getMerchantByToken($my_token);
$merchant_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{package_trans}}');

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

$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("step 3 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>3,
   'show_bar'=>true
));


/*FAILED TRANSACTION*/
if(isset($_GET['failed'])){
	if(isset($_POST['transaction_id'])){
		$error=t("Payment Failed");
	}
}

$credentials = FunctionsV3::GetIpayCredentialsAdmin();
$invoice_id = Yii::app()->functions->getLastIncrement('{{package_trans}}');
$order_id_length = 26 - strlen($invoice_id);			
$invoice_id = $invoice_id."-".strtolower(FunctionsV3::generateCode($order_id_length));
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">

  
  <h1><?php echo t("Ipay")?></h1>
  <div class="box-grey rounded">	     
  
  <?php if (!is_array($data) && count($data)<=1):?> 
    <p class="tex-danger"><?php echo t("Records not found")?></p>
  <?php else :?>
  
  
	  <?php if (isset($_GET['errormsg'])):?>
	  <p class="text-danger"><?php echo $_GET['errormsg']?></p>
	  <?php endif;?>
	  
	  <form method=POST action="https://community.ipaygh.com/gateway">
	    <?php
	    echo CHtml::hiddenField('merchant_key', trim($credentials['merchant_key']) );
	    echo CHtml::hiddenField('invoice_id',$invoice_id);
	    echo CHtml::hiddenField('total', normalPrettyPrice($amount_to_pay) );
	    echo CHtml::hiddenField('currency', Yii::app()->functions->adminCurrencyCode() );
	    	    
	    $ipn_url = websiteUrl()."/admin_ipay_ipn_url/?token=".urlencode($my_token);
	    
	    if (isset($_GET['renew'])) {	    	
	    	$ipn_url.="&package_id=".urlencode($_GET['package_id']);
	    	$ipn_url.="&renew=1";
	    }
	    	    
	    echo CHtml::hiddenField('ipn_url', $ipn_url  );
	    
	    $success_url = websiteUrl()."/admin_ipay_success_url/?invoice_id=".urlencode($invoice_id)."&token=".urlencode($my_token);
	    if (isset($_GET['renew'])) {
	    	$success_url.="&renew=1";
	    }	    
	    echo CHtml::hiddenField('success_url', $success_url);
	    echo CHtml::hiddenField('cancelled_url', websiteUrl()."/merchantsignup");    
	    ?>
	    
	    <div class="row top10">
		  <div class="col-md-3"><?php echo t("Amount")?></div>
		  <div class="col-md-8">
		    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
		  </div>
		</div>
	    
		<div class="row top10">
		  <div class="col-md-6">
	        <input type="submit" name="submit" value="<?php  echo t("Make Payment")?>" class="green-button medium inline block">
	      </div>
	    </div> <!--row-->
	    
	  </form>
  
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
   
  </div> <!--box-grey-->
  
  <?php endif;?>
  
  </div>
  </div>
</div>
</div>