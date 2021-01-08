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

?>


<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Dixipay")?></h1>
  <div class="box-grey rounded">	     

   <p class="payment-errors text-danger"></p>
   
    <form id="frm_redirect_payment" class="frm_redirect_payment"  method="POST" action="<?php echo Yii::app()->createUrl('/store/dixipay_process')?>" >
    <?php echo CHtml::hiddenField('order_id_token',$order_id_token)?>
    <?php echo CHtml::hiddenField('trans_type','buy')?>
    
     <div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>	
	
	<?php if (is_array($cards) && count($cards)>=1):?>
	  <div class="dixi_card_selection">

	   <div class="row top10">
		  <div class="col-md-3"><?php echo t("Select credit card")?></div>
		  <div class="col-md-8">
		    <?php 
		    echo CHtml::dropDownList('tokenize_id','',$cards,array(
		       'class'=>"form-control"
		    ));
		    ?>
		  </div>
		</div>  
	  
	  </div> <!--dixi_card_selection-->
	  
	  <p style="padding-left:135px;"><?php echo CHtml::checkBox('dixi_show_card_wrap',false,array(
	    'class'=>'dixi_show_card_wrap'
	  ));?>
	  <?php echo t("Enter card information")?>
	  </p>
	<?php endif;?>
	
	
	<DIV class="card_information_wrap">
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Credit Card Number")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('x_card_num',
	  ''
	  ,array(
	  'class'=>'grey-fields full-width format_card_number' ,
	  'data-validation'=>"required" ,
	  'maxlength'=>19
	  ))?>
	  </div>
	</div>
	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Exp. month")?></div>
	  <div class="col-md-8">
	      <?php echo CHtml::dropDownList('expiration_month',
	      ''
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
	      ''
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
	  ''
	  ,array(
	   'class'=>'grey-fields full-width numeric_only',       
	   'data-validation'=>"required",
	   'maxlength'=>4
	  ))?>							 
	  </div>
	</div>
	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Card holder name")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('x_first_name',
	''
	,array(
	'class'=>'grey-fields full-width',
	'data-validation'=>"required"  
	))?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Save this card")?>?</div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::checkBox('save_card',false,array(
	      'value'=>1
	    ));
	    ?>
	  </div>
	</div>	
	
  </DIV>	
  
   <div class="top25">
     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
    </div>
    
     <div class="row top10">
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	  <input type="submit" id="africa_submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>	
	
	
	
	</form>
  
    </div>
   </div>
  </div>
 </div>
</div>