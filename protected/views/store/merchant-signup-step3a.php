<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Restaurant Signup"),
   'sub_text'=>t("step 3 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>3,
   'show_bar'=>true
));
?>
<?php 
$continue=true;
$msg="";
if ($merchant=Yii::app()->functions->getMerchantByToken($_GET['internal-token'])){			
} else {
	$continue=false;
	$msg=Yii::t("default",'Sorry but we cannot find what you are looking for.');
}

$paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();   
$paypal=new Paypal($paypal_con);

if ($res_paypal=$paypal->getExpressDetail()){	
} else {
	 $continue=false;
	 $msg="Paypay Error: ".$paypal->getError();
}
?>
<div class="sections section-grey2 section-orangeform section-merchant-payment">

 <div class="container">
 <div class="inner">   
 
  <?php if ( $continue==TRUE):?>
  <h1><?php echo Yii::t("default","Paypal Verification")?></h1>
  
  <div class="box-grey rounded">	
  
  <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
  <?php echo CHtml::hiddenField('action','merchantPaymentPaypal')?>
  <?php echo CHtml::hiddenField('currentController','store')?>
  <?php echo CHtml::hiddenField('internal-token',$_GET['internal-token'])?>
  <?php echo CHtml::hiddenField('token',$_GET['token'])?>    
  
  <?php if (isset($_GET['renew'])):?>
      <?php echo CHtml::hiddenField('renew',$_GET['renew'])?>    
      <?php echo CHtml::hiddenField('package_id',$_GET['package_id'])?>    
  <?php endif;?>

  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Name")?></label>
  <span class="uk-text-bold"><?php echo $res_paypal['FIRSTNAME']." ".$res_paypal['LASTNAME']?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Paypal Email")?></label>
  <span class="uk-text-bold"><?php echo $res_paypal['EMAIL']?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Selected Package")?></label>
  <span class="uk-text-bold"><?php echo ucwords($merchant['package_name'])?></span>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Amount to pay")?></label>
  <span class="uk-text-bold"><?php echo $res_paypal['CURRENCYCODE']." ".$res_paypal['AMT']?></span>
  </div>

   
  <div class="uk-form-row">   
    <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="orange-button">
  </div>
  
  </form>
  
  </div>
  
  <?php else :?>
  <p class="text-danger"><?php echo $msg;?></p>
  <?php endif;?>
  
  </div>
  </div>
</div>