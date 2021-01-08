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


$amount_to_pay=normalPrettyPrice($amount_to_pay);
$credentials = IpayAfrica::getAdminCredentials();
$telephone= str_replace("+","",$data['contact_phone']);
$email_address = $data['contact_email'];
$order_id = FunctionsV3::lastID("package_trans")."-".time();
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="row top30">
  <div class="inner">
  <h1><?php echo t("Ipay Africa")?></h1>
  <div class="box-grey rounded">	     
  <?php if ( !empty($error)):?>
  <p class="text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
   <p class="payment-errors text-danger"></p>
          
   <form id="forms_africa" class="forms_africa"  method="POST" action="https://payments.ipayafrica.com/v3/ke"  >
   <?php
    $currency_code = adminCurrencyCode();
    $call_back = websiteUrl()."/ipay_africa_pay/?trans_type=merchantreg&token_ref=".$my_token ;
    if (isset($_GET['renew'])) {
    	$call_back.="&renew=1&package_id=".$data['package_id'];
    }
    $cst = "1";
    $crl = "0";      
    echo CHtml::hiddenField('live',$credentials['mode']);
    echo CHtml::hiddenField('oid',$order_id);
    echo CHtml::hiddenField('inv',$order_id);
    echo CHtml::hiddenField('ttl',$amount_to_pay);
    echo CHtml::hiddenField('tel',$telephone);
    echo CHtml::hiddenField('eml',$email_address);
    echo CHtml::hiddenField('vid',$credentials['vendor_id']);
    echo CHtml::hiddenField('curr',$currency_code);
    echo CHtml::hiddenField('cbk', $call_back);
    echo CHtml::hiddenField('cst', $cst );
    echo CHtml::hiddenField('crl', $crl);
    
    $datastring =  $credentials['mode'].$order_id.$order_id.$amount_to_pay.$telephone.$email_address.$credentials['vendor_id'].$currency_code.$call_back.$cst.$crl;
	$hashkey = $credentials['hashkey'];	
	$generated_hash = hash_hmac('sha1',$datastring , $hashkey);	
    echo CHtml::hiddenField('hsh',$generated_hash);
   ?>
  	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	    <?php echo FunctionsV3::prettyPrice($amount_to_pay)?>
	  </div>
	</div>
		
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Email")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::textField('email_address',$email_address,array(
	      'class'=>"form-control",
	      'data-validation'=>'required',
	      'disabled'=>true
	    ));
	    ?>
	  </div>
	</div>	
	
	<div class="row top10">
	  <div class="col-md-3"><?php echo t("Telephone number")?></div>
	  <div class="col-md-8">
	    <?php 
	    echo CHtml::textField('telephone',$telephone,array(
	      'class'=>"form-control",
	      'data-validation'=>'required',
	      'disabled'=>true
	    ));
	    ?>
	  </div>
	</div>	
  
    
     <div class="row top10">
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	  <input type="submit" id="africa_submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
	  </div>
	</div>	
	
	</form>
	
	
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