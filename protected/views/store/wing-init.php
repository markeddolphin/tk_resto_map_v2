<?php
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';

$amount_to_pay=0;
$payment_description=Yii::t("default",'Membership Package - ');

$data=Yii::app()->functions->getMerchantByToken($my_token);

if ($data){
	$amount_to_pay=normalPrettyPrice($data['package_price']);		
	$amount=str_replace(",",'',$amount_to_pay);
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
	    $amount=str_replace(",",'',$amount_to_pay);
	}
}

if ( $credentials = Wing::getAdminCredentials()){
	$url = Wing::getServices('login',$credentials['mode']);
		
	//$amount_to_pay = round($amount_to_pay,0,PHP_ROUND_HALF_DOWN);	
	$site_name = getOptionA('website_title');
	$ref = "sgnup_".Yii::app()->functions->generateRandomKey(10);
	
	$params = array(
	   'loginId'=>$credentials['login_id'],
	   'password'=>$credentials['password'],
	   'biller'=>$credentials['biller'],
	   'item'=>$payment_description,
	   'amount'=>$amount_to_pay,
	   'merchant_name'=>$site_name,
	   'order_referenceno'=>$ref,
	   'notify_url'=> websiteUrl().'/wing_notify?ref='.$ref."&trans_type=merchantreg&ref_token=$my_token",
	   'return_url'=> websiteUrl()."/merchantsignup/?ref_token=$my_token&do=step3" ,
	);		
	//dump($params); die();
	if (isset($_GET['renew'])){
		$params['notify_url']= websiteUrl().'/wing_notify?ref='.$ref."&trans_type=merchantreg&ref_token=$my_token&renew=1&package_id=$package_id";
		$params['return_url']= websiteUrl()."/merchantsignup/?ref_token=$my_token&do=step3&renew=1&package_id=$package_id";
	}
		
	$content = json_encode($params);
	if ( $token = Wing::callService($url,$content)){					
		$redirect_url = Wing::getServices("payment",$credentials['mode']);					
		$this->redirect($redirect_url."?token=".$token);
		Yii::app()->end();
	} else $error = Wing::$message;		
} else  $error = t("Credentials is not yet set for wing paymemt");
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Wing")?></h1>
  <div class="box-grey rounded">	     
  
  <?php if ( !empty($error)):?>
  <p class="text-danger"><?php echo $error;?></p>  
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