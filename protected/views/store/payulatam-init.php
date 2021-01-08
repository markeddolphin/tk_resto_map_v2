<?php
$error='';
$merchant_name=Yii::app()->functions->getOptionAdmin('website_title');
 if (empty($name)){		 	
 	$merchant_name=Yii::app()->name;
 }
		 
$step2=false;
$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');

$secret_key='';
$publishable_key='';

$my_token=isset($_GET['token'])?$_GET['token']:'';

$data=Yii::app()->functions->getMerchantByToken($my_token);

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
$extra_params='';

if (isset($_GET['renew'])) {
	$extra_params="&renew=1&package_id=".$package_id;
	if ($new_info=Yii::app()->functions->getPackagesById($package_id)){		    
	    $data['package_price']=$new_info['price'];
	    if ( $new_info['promo_price']>0){
		    $data['package_price']=$new_info['promo_price'];
	    }			
	    $data['package_name']=$new_info['title'];
	    $data['package_id']=$package_id;
	}
}

$last_id = FunctionsV3::lastID("package_trans");
$payment_ref = $last_id."-".Yii::app()->functions->generateRandomKey(6);    

if ($data){
	$payment_description.=isset($data['package_name'])?$data['package_name']:'';		
	$amount_to_pay=isset($data['package_price'])?normalPrettyPrice($data['package_price']):'';	
	
    $credentials = Pipay::getAdminCredentials();
    if(!$credentials){
    	$error=t("Credentials not valid");
    }     
} else $error=t("Package information not found");

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
          <h1><?php echo t("PayU Latam")?></h1>
          <div class="box-grey rounded">	     
          
	     <form id="frm_redirect_payment" class="frm_redirect_payment"  method="POST" action="<?php echo Yii::app()->createUrl('/store/payulatam_pay')?>" >
	     <?php 
	     echo CHtml::hiddenField('payment_type','merchantreg');        
	     echo CHtml::hiddenField('token',$my_token);
	     echo CHtml::hiddenField('ref_token',$my_token);
	     echo CHtml::hiddenField('package_id',$package_id);
	     
	     if (isset($_GET['renew'])) {
	     	echo CHtml::hiddenField('renew',1);
	     	echo CHtml::hiddenField('package_id',$package_id);
	     }
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
			  <div class="col-md-3"><?php echo t("Description")?></div>
			  <div class="col-md-8">
			    <?php echo CHtml::textField('amount',
				  $payment_description
				  ,array(
				  'class'=>'grey-fields full-width',
				  'disabled'=>true
				  ))?>
			  </div>
			</div>
			
			
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
	    echo CHtml::textField('card_name',
	    isset($data['contact_name'])?$data['contact_name']:''
	    ,array(
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
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	  <input type="submit" id="stripe_ideal_submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>	
	     

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
	         
	    </form>     
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->          