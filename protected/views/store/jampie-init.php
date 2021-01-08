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
//dump($data);
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Jampie Pay")?></h1>
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
	
	 <form action="http://jampie.com/epay2/checkout/" method="post">
	  <?php 
	  echo CHtml::hiddenField('business',getOptionA('admin_jampie_email'));
	  echo CHtml::hiddenField('type','buy');
	  echo CHtml::hiddenField('item_name',$payment_description);
	  //echo CHtml::hiddenField('item_number',$data['package_id']);
	  echo CHtml::hiddenField('item_number',Yii::app()->functions->generateRandomKey(3));
	  echo CHtml::hiddenField('amount',unPrettyPrice($amount_to_pay));
	  echo CHtml::hiddenField('shipping',0);
	  
	  if (isset($_GET['renew'])){
	  	  echo CHtml::hiddenField('return', websiteUrl()."/success_jampie/?xtrans=merchantreg&token=$my_token&renew=1&package_id=$package_id");  	      
	  	  echo CHtml::hiddenField('cancel_return',websiteUrl()."/merchantsignup/?token=$my_token&do=step3&renew=1&package_id=$package_id&");
	  } else {	  	  
	  	  echo CHtml::hiddenField('return', websiteUrl()."/success_jampie/?xtrans=merchantreg&token=$my_token&");
	  	  echo CHtml::hiddenField('cancel_return',websiteUrl()."/merchantsignup/?token=$my_token&do=step3");
	  }
	  	  
	  echo CHtml::hiddenField('currency_code',adminCurrencyCode());
	  ?>
	  <input type="submit" value="<?php echo t("Pay Now")?>" class="black-button inline medium">
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