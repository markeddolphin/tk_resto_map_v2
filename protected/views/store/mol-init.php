<?php
$db= new DbExt; $error=''; 
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{package_trans}}');
$back_url=Yii::app()->createUrl('/store/merchantsignup',array(
  'Do'=>'step3',
  'token'=>$my_token
));

if ($res=Yii::app()->functions->getMerchantByToken($my_token)){
	
	if (isset($_GET['renew'])){ 				
		if ($new_info=Yii::app()->functions->getPackagesById($package_id)){	    
			$res['package_id']=$new_info['package_id'];
			$res['package_name']=$new_info['title'];
			$res['package_price']=$new_info['price'];
			if ($new_info['promo_price']>0){
				$res['package_price']=$new_info['promo_price'];
			}			
		}
	}
		
	$merchant_id=$res['merchant_id'];
	$payment_description="Membership Package - ".$res['package_name'];
	$amount_to_pay=normalPrettyPrice($res['package_price']);
	
	//dump($amount_to_pay); dump($payment_description);	
	
	$apikey=FunctionsV3::getMollieApiKey(true);
	$locale='en_US';
	$_locale=getOptionA('admin_mol_locale');
	if(!empty($_locale)){
		$locale=$_locale;
	}
	if($apikey){			
	   spl_autoload_unregister(array('YiiBase','autoload'));
       require "Mollie/API/Autoloader.php";				   
       spl_autoload_register(array('YiiBase','autoload'));				   
	   $mollie = new Mollie_API_Client;
       $mollie->setApiKey($apikey);       
       $redirect_url=websiteUrl()."/mollieprocess/?transaction=membership&token=".$my_token;
       if (isset($_GET['renew'])){
       	   $redirect_url.="&renew=1&package_id=".$package_id;
       }
       
       /*dump($redirect_url);       
       die();*/
    
       try {
       	
           $payment = $mollie->payments->create(array(
		        "amount"      => $amount_to_pay,
		        "description" => $payment_description,
		        'locale'      => $locale,
		        "redirectUrl" => $redirect_url,
		        //'webhookUrl'=>""
		   ));	
		   				   	
		   $params=array(
			   'package_id'=>$res['package_id'],	          
		       'merchant_id'=>$res['merchant_id'],
		       'price'=>$res['package_price'],
		       'payment_type'=>"mol",
		       'membership_expired'=>$res['membership_expired'],
		       'date_created'=>FunctionsV3::dateNow(),
		       'ip_address'=>$_SERVER['REMOTE_ADDR'],		       
		       'TRANSACTIONID'=>$payment->id,
		       'TOKEN'=>$my_token		       
	       );					 		         	       
  	       $db->insertData("{{package_trans}}",$params);	
	       $this->redirect($payment->links->paymentUrl);					   
		   Yii::app()->end();
		   
       } catch (Exception $e){
       	  $error=$e->getMessage();
       }                   
       		
	} else $error= t("API key is not yet set. please try again later");
} else $error=Yii::t("default","Failed. Cannot process payment");  

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
       
          <h1>&nbsp;</h1>
          <div class="box-grey rounded">	
          <?php if(!empty($error)):?>
           <p><?php echo $error;?></p>
          <?php endif;?>
          </div>
          
          <a href="<?php echo $back_url?>">
          <?php echo t("Click here to change payment option")?>
          </a>
          
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->