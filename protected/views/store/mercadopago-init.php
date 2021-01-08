<?php
$extra_params='';
$amount_to_pay=0;
$card_fee=0;
$merchant_id=0;
$error='';

$my_token=isset($_GET['token'])?$_GET['token']:'';
if(isset($_GET['reference_id'])){
   $my_token=isset($_GET['reference_id'])?$_GET['reference_id']:'';	
}

$payment_code=mercadopagoWrapper::paymentCode();
$data=Yii::app()->functions->getMerchantByToken($my_token);

$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$trans_type='reg';
$reference_id = $my_token;
$payment_description='';

$cancel_url  = websiteUrl()."/merchantsignup?Do=step3b&token=$my_token&gateway=$payment_code";
$success_url = websiteUrl()."/mercadopago_success?&trans_type=$trans_type";
$failure_url = websiteUrl()."/mercadopago_failure?&trans_type=$trans_type";

$back_url = websiteUrl()."/merchantsignup?Do=step3&token=$my_token";

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
	$failure_url.=$extra_params;
	$back_url.=$extra_params;
}

if ($data){
	if ($credentials = mercadopagoWrapper::getAdminCredentials()){
		
		$merchant_id = $data['merchant_id'];	
		
		$payment_description= Yii::t("default","Membership Payment by [restaurant_name]. Package name : [package_name]",array(
		   '[package_name]'=>isset($data['package_name'])?$data['package_name']:'',
		   '[restaurant_name]'=>$data['restaurant_name']
		));
		
		$amount_to_pay = Yii::app()->functions->normalPrettyPrice($data['package_price']);		
		$card_fee = $credentials['card_fee'];
		if($credentials['card_fee']>0.001){
			$amount_to_pay = unPrettyPrice($amount_to_pay) + unPrettyPrice($credentials['card_fee']);
		}					
				
		try {
			
			$params=array(
			  'title'=>$payment_description,
			  'quantity'=>1,
			  'currency_id'=>FunctionsV3::getCurrencyCode(),
			  'unit_price'=>$amount_to_pay,
			  'email'=>$data['contact_email'],
			  'external_reference'=>$reference_id,
			  'success'=>$success_url,
			  'failure'=>$failure_url,
			  'pending'=>$cancel_url,
			);			
			$resp = mercadopagoWrapper::createPayment($credentials,$params);			
			$this->redirect($resp);
			Yii::app()->end();
			
		} catch (Exception $e){
			$error = $e->getMessage();
		}
		
		
	} else $error = t("invalid payment credentails");
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");

$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using Mercadopago")?></h1>
          <div class="box-grey rounded">	 
          
          <p class="text-danger"><?php echo $error;?></p>
          
          <div class="top25">
	     <a href="<?php echo $back_url?>">
	     <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
	    </div>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->

