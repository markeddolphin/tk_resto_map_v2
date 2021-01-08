<?php
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');

$vogue_merchant_id=getOptionA('admin_vog_merchant_id');

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
		
		$is_demo=false;
		if($vogue_merchant_id=="demo"){
	    	$is_demo=true;
	    }
	    
	    if ( $vog_res=voguepayClass::getTransaction($_POST['transaction_id'],$is_demo)){
			if(isset($vog_res['response_message'])){
				$error = Yii::t("default", "Payment failed reason : [reason]",array(
				  '[reason]'=>$vog_res['response_message']
				));
			} 
		}
						
	}
}

?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Pay using")." ".t("voguepay")?></h1>
  <div class="box-grey rounded">	     
  <?php if ( !empty($error)):?>
  <p class="text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
          
  	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>
	
	<div class="row top10">
	<div class="col-md-12">
	
	 <form method='POST' action='https://voguepay.com/pay/'>
	  <?php 
	  echo CHtml::hiddenField('v_merchant_id',$vogue_merchant_id);
	  echo CHtml::hiddenField('merchant_ref',$merchant_ref);
	  echo CHtml::hiddenField('memo',$payment_description);
	  echo CHtml::hiddenField('total',Yii::app()->functions->unPrettyPrice($amount_to_pay));
	  echo CHtml::hiddenField('notify_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/store/voguepaynotify',array(
	    'token'=>$my_token,
	  )));
	  	  
	  echo CHtml::hiddenField('fail_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/store/merchantsignup',array(
	    'Do'=>"step3b",
	    'token'=>$my_token,
	    'gateway'=>'vog',
	    'failed'=>1
	  )));
	  if (isset($_GET['renew'])) {
	  	 echo CHtml::hiddenField('success_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/store/voguepaysuccess',array(
	  	   'renew'=>1,
	  	   'token'=>$my_token,
	  	   'package_id'=>$data['package_id']
	  	 )));
	  } else {
	  	  echo CHtml::hiddenField('success_url',FunctionsV3::getHostURL().Yii::app()->createUrl('/store/voguepaysuccess',array(
	  	    'token'=>$my_token
	  	  )));
	  }	   	  
	  
	  echo CHtml::hiddenField('cur', Yii::app()->functions->adminCurrencyCode() );	  
	  ?>
	   <input type='image' src='//voguepay.com/images/buttons/checkout_blue.png' alt='Submit' />
	 </form>
	
    </div>
    </div>
	
  <?php endif;?>
    
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
</div>