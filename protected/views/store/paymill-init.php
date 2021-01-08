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

if(!$credentials=KPaymill::getAdminCredentials()){
	$error = t("Paymill settings is not properly configured");
} else {
	$public_key=$credentials['public_key'];
	
	$cs = Yii::app()->getClientScript();			
	$cs->registerScript(
	  'PAYMILL_PUBLIC_KEY',
	  "var PAYMILL_PUBLIC_KEY='$public_key';",
	CClientScript::POS_HEAD
	);		
	$cs->registerScriptFile("https://bridge.paymill.com/",CClientScript::POS_END); 	
}

$amount_to_pay=normalPrettyPrice($amount_to_pay);
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
	
	 <form id="forms-paymill" class="forms"  method="POST" >
	 
	  <?php 
	   echo CHtml::hiddenField('label_loading',t("Please wait"));
	   echo CHtml::hiddenField('label_paynow',t("Pay Now"));
	   
	   echo CHtml::hiddenField('paymill_token');
	   echo CHtml::hiddenField('trans_type','merchantreg');
	   echo CHtml::hiddenField('action','paymill_transaction');
	   echo CHtml::hiddenField('amount',$amount_to_pay);	   	   	   	   	  
	   echo CHtml::hiddenField('payment_description',$payment_description);
	   echo CHtml::hiddenField('my_token',$my_token);
	   echo CHtml::hiddenField('package_id',$package_id);
	   echo CHtml::hiddenField('x_amount',$amount_to_pay*100);
	   echo CHtml::hiddenField('x_currency_code',Yii::app()->functions->adminCurrencyCode());
	   if (isset($_GET['renew'])){
	      echo CHtml::hiddenField('renew',1);	
	   } else {
	   	  echo CHtml::hiddenField('renew',0);	
	   }
	  ?> 
	  
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
	  <div class="col-md-3">&nbsp;</div>
	  <div class="col-md-8">
	  <input type="submit" id="paymill_submit" value="<?php echo t("Pay Now")?>" class="black-button inline medium">
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