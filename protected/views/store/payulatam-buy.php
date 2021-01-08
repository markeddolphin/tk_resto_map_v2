<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$cs = Yii::app()->getClientScript();
if(isset($_GET['debug'])){
   dump($credentials);
}
?>


<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("PayU Latam")?></h1>
  <div class="box-grey rounded">	     
  
  
  <form id="frm_redirect_payment" class="frm_redirect_payment"  method="POST" action="<?php echo Yii::app()->createUrl('/store/payulatam_pay')?>" >
    <?php 
    echo CHtml::hiddenField('payment_type','buy');        
    echo CHtml::hiddenField('order_id_token',$order_id_token);
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
	
	<?php if(is_array($saved_cards) && count($saved_cards)>=1):?>
	<DIV class="select_card_div">
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Select card")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::dropDownList('card_id','',$saved_cards,array(
	      'class'=>"form-control has_card_saved",
	      'required'=>true
	    ));
	    ?>
	  </div>
	</div>	
	
	<div class="row top10">
	   <div class="col-md-3"></div>
	   <div class="col-md-8"><a href="javascript:;" class="enter_card_manually"><?php echo t("Click here to enter card")?></a></div>
	</div>
	</DIV>
	<?php endif;?>
	
   <DIV class="enter_card_div">
   
   <div class="row top10">
	  <div class="col-md-3"><?php echo t("Card Type")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::dropDownList('card_method','VISA',(array) Payulatam::cardType(),array(
	      'class'=>"form-control",
	      'required'=>true
	    ));
	    ?>
	  </div>
	</div>	
   
    <div class="row top10">
	  <div class="col-md-3"><?php echo t("Card Holder Name")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::textField('card_name',Yii::app()->functions->getClientName(),array(
	      'class'=>"form-control",
	      'required'=>true
	    ));
	    ?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Card number")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::textField('credit_card_number','',array(
	      'class'=>"form-control",
	      'maxlength'=>16,
	      'required'=>true
	    ));
	    ?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Expiration")?></div>
	  <div class="col-md-4">
	    <?php 	    
	    echo CHtml::dropDownList('expiration_month','',Yii::app()->functions->ccExpirationMonth(),array(
	     'class'=>"form-control",
	     'required'=>true
	    ));
	    ?>
	  </div>
	  <div class="col-md-4">
	    <?php 	    
	    echo CHtml::dropDownList('expiration_yr','',Yii::app()->functions->ccExpirationYear(),array(
	     'class'=>"form-control",
	     'required'=>true
	    ));
	    ?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Security Code (CVV)")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::textField('cvv','',array(
	      'class'=>"form-control",
	      'maxlength'=>4,
	      'required'=>true
	    ));
	    ?>
	  </div>
	</div>	
	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Save Card")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::checkBox('saved_cards',false,array(
	     'value'=>1
	    ));
	    ?>
	  </div>
	</div>	
	
	
	
	</DIV>	
	
	
	 <div class="row top10">
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	  <input type="submit" id="stripe_ideal_submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>	
  
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
    
   </form>

    </div>
   </div>
  </div>
 </div>
</div>  