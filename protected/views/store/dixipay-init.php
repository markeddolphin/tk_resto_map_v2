<?php
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');

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
	    
	    $amount_to_pay = unPrettyPrice($data['package_price']);	    
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

$publishable_key='';
$amount_to_pay=normalPrettyPrice($amount_to_pay);
$payment_description = addslashes($payment_description);
$stripe_amount = $amount_to_pay*100;
$stripe_currency = adminCurrencyCode();
$stripe_client_name = $data['restaurant_name'];
$stripe_return_url = websiteUrl()."/dixipay_process?trans_type=merchantreg&ref_token=".$my_token;
if (isset($_GET['renew'])){
	$stripe_return_url.="&package_id=".$package_id;
	$stripe_return_url.="&renew=1";
}
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Paymill")?></h1>
  <div class="box-grey rounded">	     
  <?php if ( !empty($error)):?>
  <p class="text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
   <p class="payment-errors text-danger"></p>
          
  	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>
	
	<div class="row top10">
	<div class="col-md-12">
	
	<form id="frm_redirect_payment" class="frm_redirect_payment"  method="POST" action="<?php echo Yii::app()->createUrl('/store/dixipay_process')?>" >
    <?php     
    echo CHtml::hiddenField('trans_type','merchantreg');
    echo CHtml::hiddenField('ref_token',$my_token);
    if (isset($_GET['renew'])){
    	echo CHtml::hiddenField('package_id',$package_id);
    	echo CHtml::hiddenField('renew',1);
    }
    ?>
    
     <div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Credit Card Number")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('x_card_num',
	  ''
	  ,array(
	  'class'=>'grey-fields numeric_only full-width' ,
	  'data-validation'=>"required",
	  'maxlength'=>16
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