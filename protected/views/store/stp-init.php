<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("step 3 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>3,
   'show_bar'=>true
));

$extra_params='';
$amount_to_pay=0;
$card_fee=0;
$merchant_id=0;

$my_token=isset($_GET['token'])?$_GET['token']:'';
$payment_code=StripeWrapper::paymentCode();
$data=Yii::app()->functions->getMerchantByToken($my_token);

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$trans_type='reg';
$reference_id = $my_token;

$cancel_url = websiteUrl()."/merchantsignup?Do=step3b&token=$my_token&gateway=$payment_code";
$success_url = websiteUrl()."/stripe_success?reference_id=".urlencode($reference_id)."&trans_type=$trans_type";

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
	$cancel_url.=$extra_params;
	$success_url.=$extra_params;
}
if ($data){
	$merchant_id = $data['merchant_id'];	
	if ($credentials = StripeWrapper::getAdminCredentials()){		
		$amount_to_pay = Yii::app()->functions->normalPrettyPrice($data['package_price']);
		$card_fee = $credentials['card_fee'];
		if($credentials['card_fee']>0.001){
			$amount_to_pay = unPrettyPrice($amount_to_pay) + unPrettyPrice($credentials['card_fee']);
		}			
		$amount_to_pay = unPrettyPrice($amount_to_pay)*100;
				
		$payment_description= Yii::t("default","Membership Payment by [restaurant_name]. Package name : [package_name]",array(
		   '[package_name]'=>isset($data['package_name'])?$data['package_name']:'',
		   '[restaurant_name]'=>$data['restaurant_name']
		));
		
		$description = Yii::t("default","Membership Payment reference number [token]",array(
		  '[token]'=>$my_token
		));
		
		try {
			
			$params = array(
			   'customer_email' => $data['contact_email'],					   
			   'payment_method_types'=>array('card'),
			   'client_reference_id'=>$trans_type."-".$reference_id,					   
			   'line_items'=>array(
			     array(
			         'name'=>$payment_description,
				     'description'=>$description,
				     'amount'=>$amount_to_pay,
				     'currency'=>FunctionsV3::getCurrencyCode(),
				     'quantity'=>1
			     )
			   ),					   
			   'success_url'=>$success_url,
			   'cancel_url'=>$cancel_url,
			);					
			//dump($params);die();
			
			$resp  =  StripeWrapper::createSession($credentials['secret_key'],$params);								
		    $stripe_session=$resp['id'];
		    $payment_intent=$resp['payment_intent'];
		    
		    /*LOGS THE PAYMENT INTENT*/
			$db=new DbExt();
			$db->updateData("{{merchant}}",array(
			  'payment_gateway_ref'=>$payment_intent
			),'merchant_id',$merchant_id);
		    		    		    
		    $cs = Yii::app()->getClientScript();
		    $cs->registerScriptFile("https://js.stripe.com/v3/");
					
			$publish_key = $credentials['publish_key'];
			$publish_key = "Stripe('$publish_key')";
			
			$cs->registerScript(
			  'stripe',
			  'var stripe = '.$publish_key.';
			  ',
			  CClientScript::POS_HEAD
			);					
			$cs->registerScript(
			  'stripe_session',
			 "var stripe_session='$stripe_session';",
			  CClientScript::POS_HEAD
			);		
						
		
		} catch (Exception $e) {
			$error = Yii::t("default","Caught exception: [error]",array(
			  '[error]'=>$e->getMessage()
			));
		}        	
			
	} else $error = t("invalid payment credentials");
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");
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
          
			<table class="table">
			<tr>
			<tr>
			<td><?php echo t("Description")?></td>
			<td><?php echo $payment_description?></td> 
			</tr>
			<?php if($card_fee>0.001):?>
			
			<tr>
			<td><?php echo t("Amount")?></td>
			<td><?php echo FunctionsV3::prettyPrice( ($amount_to_pay/100)-$card_fee  )?></td>
			</tr> 
			
			<tr>
			<td><?php echo t("Card fee")?></td>
			<td><?php echo FunctionsV3::prettyPrice($card_fee)?></td> 
			</tr>
			
			<tr>
			<td><?php echo t("Total")?></td>
			<td><?php echo FunctionsV3::prettyPrice( ($amount_to_pay) /100)?></td>
			</tr> 
			
			<?php else :?>
			
			<tr>
			<td><?php echo t("Total")?></td>
			<td><?php echo FunctionsV3::prettyPrice($amount_to_pay/100)?></td>
			</tr> 
			
			
			<?php endif;?> 
			<tr>
			<td colspan="2">
			<button class="btn paynow_stripe"><?php echo t("Pay now")?></button>  
			</td>
			</tr>
			</table>
          
          <?php endif;?>       
          
		 <p>
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
	    </p>

          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->          