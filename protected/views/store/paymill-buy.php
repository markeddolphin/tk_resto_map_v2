<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$data_post = $_POST;
$public_key=$credentials['public_key'];

$cs = Yii::app()->getClientScript();			
$cs->registerScript(
  'PAYMILL_PUBLIC_KEY',
  "var PAYMILL_PUBLIC_KEY='$public_key';",
CClientScript::POS_HEAD
);		
$cs->registerScriptFile("https://bridge.paymill.com/",CClientScript::POS_END); 

$amount_to_pay=normalPrettyPrice($amount_to_pay);
?>


<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Paymill")?></h1>
  <div class="box-grey rounded">	     

   <p class="payment-errors text-danger"></p>
  
   <form id="forms-paymill" class="forms"  method="POST" >
   
   <?php 
   echo CHtml::hiddenField('label_loading',t("Please wait"));
   echo CHtml::hiddenField('label_paynow',t("Pay Now"));
	   
   echo CHtml::hiddenField('paymill_token');
   echo CHtml::hiddenField('trans_type','buy');
   echo CHtml::hiddenField('action','paymill_transaction');
   echo CHtml::hiddenField('amount',$amount_to_pay);
   
   echo CHtml::hiddenField('x_amount',$amount_to_pay*100);
   echo CHtml::hiddenField('x_currency_code',Yii::app()->functions->adminCurrencyCode());
   echo CHtml::hiddenField('merchant_id',$merchant_id);
   echo CHtml::hiddenField('payment_description',$payment_description);
   echo CHtml::hiddenField('order_id',$order_id);
   ?>
   
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('amount',
		  normalPrettyPrice($amount_to_pay)
		  ,array(
		  'class'=>'grey-fields full-width',
		  'disabled'=>true
		  ))?>
	  </div>
	</div>
	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Credit Card Number")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('x_card_num',
	  '4111111111111111'
	  ,array(
	  'class'=>'grey-fields numeric_only full-width' ,
	  'data-validation'=>"required"  
	  ))?>
	  </div>
	</div>
	
   <div class="row top10">
	  <div class="col-md-3"><?php echo t("Exp. month")?></div>
	  <div class="col-md-8">
	      <?php echo CHtml::dropDownList('expiration_month',
	      isset($data_post['expiration_month'])?$data_post['expiration_month']:''
	      ,
	      Yii::app()->functions->ccExpirationMonth()
	      ,array(
	       'class'=>'grey-fields full-width',
	       'placeholder'=>Yii::t("default","Exp. month"),
	       'data-validation'=>"required"  
	      ))?>
	  </div>
	</div>
						
    <div class="row top10">
	  <div class="col-md-3"><?php echo t("Exp. year")?></div>
	  <div class="col-md-8">
	   <?php echo CHtml::dropDownList('expiration_yr',
	      isset($data_post['expiration_yr'])?$data_post['expiration_yr']:''
	      ,
	      Yii::app()->functions->ccExpirationYear()
	      ,array(
	       'class'=>'grey-fields full-width',
	       'placeholder'=>Yii::t("default","Exp. year") ,
	       'data-validation'=>"required"  
	      ))?>
	  </div>
	</div>
   				
   <div class="row top10">
	  <div class="col-md-3"><?php echo t("CCV")?></div>
	  <div class="col-md-8">
      <?php echo CHtml::textField('cvv',
      isset($data_post['cvv'])?$data_post['cvv']:''
      ,array(
       'class'=>'grey-fields full-width numeric_only',       
       'data-validation'=>"required",
       'maxlength'=>4
      ))?>							 
	  </div>
	</div>
	

    <div class="row top10" >
	  <div class="col-md-3"><?php echo t("Card holder name")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('x_card_holder_name',
		  isset($data_post['x_card_holder_name'])?$data_post['x_card_holder_name']:''
		  ,array(
		  'class'=>'grey-fields full-width',
		  //'data-validation'=>"required"  
		  ))?>
	  </div>
	</div>		
	
	
   <div class="row top10">
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	  <input type="submit" id="paymill_submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>	
	
   </form>
  
  
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
  
    </div>
   </div>
  </div>
 </div>
</div>