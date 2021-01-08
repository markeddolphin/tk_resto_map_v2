<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));
//dump($credentials);
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
		
	<?php echo CHtml::beginForm( Yii::app()->createUrl('/store/rzrverify',array('xid'=>$data['order_id'])) , 'post');?>
    <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $credentials['key_id']?>"
    data-amount="<?php echo $amount;?>"
    data-buttontext="<?php echo t("Pay Now")?>"
    data-name="<?php echo clearString($data['merchant_name'])?>"
    data-description="<?php echo $payment_description?>"
    data-image="<?php //echo FrontFunctions::getLogoURL();?>"
    data-prefill.name="<?php echo $_SESSION['kr_client']['first_name']." ".$_SESSION['kr_client']['last_name']?>"
    data-prefill.email="<?php echo $_SESSION['kr_client']['email_address']?>"
    data-prefill.contact="<?php echo $_SESSION['kr_client']['contact_phone']?>"  
    data-theme.color="#F37254"></script>
    <input type="hidden" value="<?php echo $data['order_id']?>" name="hidden">	    
    
    <p style="margin-top:20px;" class="text-small">
    <?php echo t("Please don't close the window during payment, wait until you redirected to receipt page")?></p> 
    
    <?php echo CHtml::endForm() ; ?>
    
    </div>
    </div>
	
  <?php endif;?>
    
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
  
    </div>
   </div>
  </div>
 </div>
</div>