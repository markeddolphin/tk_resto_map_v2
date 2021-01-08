<?php
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');

$mode=strtolower(getOptionA('admin_rzr_mode'));
if ( $mode=="sandbox"){
	$credentials['key_id']=getOptionA('admin_razor_key_id_sanbox');
	$credentials['key_secret']=getOptionA('admin_razor_secret_key_sanbox');
} else {
	$credentials['key_id']=getOptionA('admin_razor_key_id_live');
	$credentials['key_secret']=getOptionA('admin_razor_secret_key_live');
}		

$data=Yii::app()->functions->getMerchantByToken($my_token);

if ($data){
	$amount_to_pay=normalPrettyPrice($data['package_price']);
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
	    
	    $amount_to_pay = normalPrettyPrice($data['package_price']);
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
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Pay using")." ".t("Razorpay")?></h1>
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
		
	<?php if (isset($_GET['renew'])) :?>
	
		<?php echo CHtml::beginForm( Yii::app()->createUrl('/store/rzrvalidate',array(
		  'token'=>$my_token,
		  'renew'=>1,
		  'package_id'=>$package_id
		)) , 'post');?>

	<?php else :?>
	
		<?php echo CHtml::beginForm( Yii::app()->createUrl('/store/rzrvalidate',array(
		  'token'=>$my_token,		  
		)) , 'post');?>
	
	<?php endif;?>
	
	
    <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $credentials['key_id']?>"
    data-amount="<?php echo $amount;?>"
    data-buttontext="<?php echo t("Pay Now")?>"
    data-name="<?php echo clearString($data['merchant_name'])?>"
    data-description="<?php echo $payment_description?>"
    data-image="<?php //echo FrontFunctions::getLogoURL();?>"
    data-prefill.name="<?php echo isset($data['contact_name'])?$data['contact_name']:""?>"
    data-prefill.email="<?php echo isset($data['contact_email'])?$data['contact_email']:""?>"
    data-prefill.contact="<?php echo isset($data['contact_phone'])?$data['contact_phone']:""?>"  
    data-theme.color="#F37254"></script>
    <input type="hidden" value="<?php echo $my_token?>" name="token">	    
    
    <p style="margin-top:20px;" class="text-small">
    <?php echo t("Please don't close the window during payment, wait until you redirected to receipt page")?></p> 
    
    <?php echo CHtml::endForm() ; ?>
    
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