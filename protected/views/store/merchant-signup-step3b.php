<?php
require_once('stripe/lib/Stripe.php');

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

$payment_code=Yii::app()->functions->paymentCode("stripe");

$data=Yii::app()->functions->getMerchantByToken($my_token);

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
$extra_params='';

if (isset($_GET['renew'])) {
	$extra_params="renew/1/package_id/".$package_id;
	if ($new_info=Yii::app()->functions->getPackagesById($package_id)){		    
	    $data['package_price']=$new_info['price'];
	    if ( $new_info['promo_price']>0){
		    $data['package_price']=$new_info['promo_price'];
	    }			
	    $data['package_name']=$new_info['title'];
	    $data['package_id']=$package_id;
	}
}

if ($data){			
		
	$mode=Yii::app()->functions->getOptionAdmin('admin_stripe_mode');   				
	if ( $mode=="Sandbox"){
		$secret_key=Yii::app()->functions->getOptionAdmin('admin_sanbox_stripe_secret_key');   
		$publishable_key=Yii::app()->functions->getOptionAdmin('admin_sandbox_stripe_pub_key');   
	} elseif ($mode=="live"){
		$secret_key=Yii::app()->functions->getOptionAdmin('admin_live_stripe_secret_key');   
		$publishable_key=Yii::app()->functions->getOptionAdmin('admin_live_stripe_pub_key');   
	}	
	
	if ( !empty($mode) && !empty($secret_key) && !empty($publishable_key) ){
		$amount_to_pay=isset($data['package_price'])?Yii::app()->functions->standardPrettyFormat($data['package_price']):'';
		$amount_to_pay=is_numeric($amount_to_pay)?unPrettyPrice($amount_to_pay*100):'';
		$amount_to_pay=Yii::app()->functions->normalPrettyPrice2($amount_to_pay);
			
		$payment_description.=isset($data['package_name'])?$data['package_name']:'';	
				
		$stripe = array(
	     "secret_key"      => $secret_key,
	     "publishable_key" => $publishable_key
	    );
	    Stripe::setApiKey($stripe['secret_key']);
    
	} else $error=Yii::t("default","Stripe payment is not properly configured on admin portal.");
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");

if (isset($_POST)){
	if (is_array($_POST) && count($_POST)>=1){		
		$step2=true;
		$token=isset($_POST['stripeToken'])?$_POST['stripeToken']:'';
				
		try {
			$customer = Stripe_Customer::create(array(
		      'email' => isset($_POST['stripeEmail'])?$_POST['stripeEmail']:'',
		      'card'  => $token
		    ));
		    	           
	        $charge = Stripe_Charge::create(array(
	          'customer' => $customer->id,
	          'amount'   => $amount_to_pay,
	          'currency' => Yii::app()->functions->adminCurrencyCode()
	        ));	        
	        	        
	        $chargeArray = $charge->__toArray(true);            
	        $db_ext=new DbExt;
	        
	        $params_logs=array(
	          'package_id'=>$data['package_id'],	          
	          'merchant_id'=>$data['merchant_id'],
	          'price'=>$data['package_price'],
	          'payment_type'=>$payment_code,
	          'membership_expired'=>$data['membership_expired'],
	          'date_created'=>FunctionsV3::dateNow(),
	          'ip_address'=>$_SERVER['REMOTE_ADDR'],
	          'PAYPALFULLRESPONSE'=>json_encode($chargeArray)
	        );
	        
	        if (isset($_GET['renew'])){	        	
	        	$membership_info=Yii::app()->functions->upgradeMembership($data['merchant_id'],$package_id);
	        	
	        	$params_logs['membership_expired']=$membership_info['membership_expired'];
	        	
	        	$params_update=array(
				  'package_id'=>$package_id,
				  'package_price'=>$membership_info['package_price'],
				  'membership_expired'=>$membership_info['membership_expired'],				  
				  'status'=>'active'
			 	 );					 	 
				 $db_ext->updateData("{{merchant}}",$params_update,'merchant_id',$data['merchant_id']);	     	        
	        }
	        	        
	        $db_ext->insertData("{{package_trans}}",$params_logs);
	        	        
	        $db_ext->updateData("{{merchant}}",
							   array(
							     'payment_steps'=>3,
							     'membership_purchase_date'=>FunctionsV3::dateNow()
							   ),'merchant_id',$data['merchant_id']);							   
	                    
            if (isset($_GET['renew'])){            	
            	header('Location: '. Yii::app()->createUrl('/store/renewsuccesful'));
            } else {
            	
            	/*SEND EMAIL*/
            	FunctionsV3::sendWelcomeEmailMerchant($data);
            	FunctionsV3::sendMerchantActivation($data, $data['activation_key']);
            	
            	header('Location: '. Yii::app()->createUrl("store/merchantsignup",array(
                  'Do'=>"step4",
                  'token'=>$my_token
                )));	
            }       
            
	    } catch (Exception $e)   {
	    	$error=$e;
	    }    
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
?>


<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using Stripe Payment")?></h1>
          <div class="box-grey rounded">	     
          
          
          <?php if ( !empty($error)):?>
             <p class="text-danger"><?php echo $error;?></p>  
          <?php else :?>
              <?php if ( $step2==TRUE):?>
              <?php else :?>
              			  		
			  <?php echo CHtml::beginForm( Yii::app()->request->baseUrl."/store/merchantsignup/do/step3b/token/$my_token/$extra_params" , 'post');?>

			  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
			          data-key="<?php echo $stripe['publishable_key']; ?>"
			          data-name="<?php echo ucwords($merchant_name);?>"
			          data-amount="<?php echo $amount_to_pay;?>" 
			          data-currency="<?php echo Yii::app()->functions->adminCurrencyCode();?>"
			          data-description="<?php echo ucwords($payment_description);?>">
			  </script>
			  <?php echo CHtml::endForm() ; ?>
			  
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