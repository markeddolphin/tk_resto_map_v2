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

$payment_code='pipay';

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
          <h1><?php echo t("Pay using Pi pay")?></h1>
          <div class="box-grey rounded">	     
          
          
          <?php if ( !empty($error)):?>
             <p class="text-danger"><?php echo $error;?></p>  
          <?php else :?>
              <?php if ( $step2==TRUE):?>
              <?php else :?>
              
			  
               <?php if ($credentials['mode']=="sandbox"):?>
			   <form method="post" action="https://onlinepayment-test.pipay.com/starttransaction">
			   <?php else :?>
			   <form method="post" action="https://onlinepayment.pipay.com/starttransaction">
			   <?php endif;?>
			  
			    <?php       
			    echo CHtml::hiddenField('mid', $credentials['pipay_merchant_id']);
			    echo CHtml::hiddenField('did', $credentials['pipay_device_id']);
			    echo CHtml::hiddenField('sid', $credentials['pipay_store_id']);    
			    
			    echo CHtml::hiddenField('lang', Pipay::language() );    
			    echo CHtml::hiddenField('orderid', $payment_ref );     
			    echo CHtml::hiddenField('orderDesc', $payment_description ); 
			    echo CHtml::hiddenField('orderAmount', $amount_to_pay ); 
			    echo CHtml::hiddenField('currency', yii::app()->functions->adminCurrencyCode() );    
			    echo CHtml::hiddenField('payerEmail', isset($data['contact_email'])?$data['contact_email']:'' ); 
			    echo CHtml::hiddenField('orderDate', date("c") );     
			    echo CHtml::hiddenField('payMethod', Pipay::payMethod() ); 
			    echo CHtml::hiddenField('trType', Pipay::trType() ); 
			    echo CHtml::hiddenField('var1', $last_id); 
			    
			    $digest = $credentials['pipay_merchant_id'];
			    $digest.=$payment_ref;
			    $digest.=$amount_to_pay;
			    //dump($digest);
			    $digest=md5($digest);    
			    //dump($digest);
			    
			    echo CHtml::hiddenField('confirmURL', websiteUrl()."/pipayconfirm/?token=".$my_token."&transtype=signup".$extra_params ); 
			    echo CHtml::hiddenField('cancelURL', Yii::app()->createUrl('merchantsignup',array(
			      'Do'=>"step3",
			      'token'=>$my_token
			    ))); 
			    echo CHtml::hiddenField('digest', $digest ); 
			    ?>
			    
			    <div class="row top10">
				  <div class="col-md-3"><?php echo t("Amount")?></div>
				  <div class="col-md-8">
				    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
				  </div>
				</div>
			    
				<div class="row top10">
				  <div class="col-md-6">
			        <input type="submit" name="submit" value="<?php  echo t("Make Payment")?>" class="green-button medium inline block">
			      </div>
			    </div> <!--row-->
			    
			  </form>
              
			  
			  <p style="margin-top:20px;" class="text-small"><?php echo t("Please don't close the window during payment, wait until you redirected to another page")?></p>
			  
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
              
              <?php endif;?>
          <?php endif;?>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->