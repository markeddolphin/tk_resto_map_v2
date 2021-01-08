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
  <h1><?php echo t("Ipay Africa")?></h1>
  <div class="box-grey rounded">	     

   <p class="payment-errors text-danger"></p>
   
    <form id="forms_africa" class="forms_africa"  method="POST" action="https://payments.ipayafrica.com/v3/ke"  >
    
    <?php 
    $currency_code = adminCurrencyCode();
    $call_back = websiteUrl()."/ipay_africa_pay/?trans_type=buy&order_id=".$order_id ;
    $cst = "1";
    $crl = "0";    
    echo CHtml::hiddenField('live',$credentials['mode']);
    echo CHtml::hiddenField('oid',$order_id);
    echo CHtml::hiddenField('inv',$order_id);
    echo CHtml::hiddenField('ttl',$amount_to_pay);
    echo CHtml::hiddenField('tel',$telephone);
    echo CHtml::hiddenField('eml',$email_address);
    echo CHtml::hiddenField('vid',$credentials['vendor_id']);
    echo CHtml::hiddenField('curr',$currency_code);
    echo CHtml::hiddenField('cbk', $call_back);
    echo CHtml::hiddenField('cst', $cst );
    echo CHtml::hiddenField('crl', $crl);
    
    $datastring =  $credentials['mode'].$order_id.$order_id.$amount_to_pay.$telephone.$email_address.$credentials['vendor_id'].$currency_code.$call_back.$cst.$crl;
	$hashkey = $credentials['hashkey'];	
	$generated_hash = hash_hmac('sha1',$datastring , $hashkey);	
    echo CHtml::hiddenField('hsh',$generated_hash);
    ?>
    
    <div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Email")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::textField('email_address',$email_address,array(
	      'class'=>"form-control",
	      'data-validation'=>'required',
	      'disabled'=>true
	    ));
	    ?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Telephone number")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::textField('telephone',$telephone,array(
	      'class'=>"form-control",
	      'data-validation'=>'required',
	      'disabled'=>true
	    ));
	    ?>
	  </div>
	</div>	
  
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