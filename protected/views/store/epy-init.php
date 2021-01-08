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

$db_ext=new DbExt;
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
$is_merchant=true;

$amount_to_pay=0;

$back_url=Yii::app()->request->baseUrl."/store/merchantSignup/Do/step3/token/$my_token";
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{package_trans}}');

$forms='';

if ( $res=Yii::app()->functions->getMerchantByToken($my_token)){ 		
	$mode=Yii::app()->functions->getOptionAdmin('admin_mode_epaybg');	
	if ($mode=="sandbox"){
		$params['mode']='sandbox';
		$min=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_min');
		$secret=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_secret');
		
		$page=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_request');
		$lang=Yii::app()->functions->getOptionAdmin('admin_sandbox_epaybg_lang');
		
	} else {
		$params['mode']='live';
		$min=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_min');
		$secret=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_secret');
		
		$page=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_request');
		$lang=Yii::app()->functions->getOptionAdmin('admin_live_epaybg_lang');
	}
	
	$payment_description="Membership Package - ".$res['package_name'];
	
	$amount_to_pay=normalPrettyPrice($res['package_price']);	
	$amount_to_pay=number_format($amount_to_pay,2,'.','');	
			
	$params['MIN']=$min;
	$params['INVOICE']=$payment_ref;
	$params['AMOUNT']=$amount_to_pay;
	$params['CURRENCY']=adminCurrencyCode();
	$params['EXP_TIME']=date('d.m.Y', strtotime ('+5 days'));
	$params['DESCR']=$payment_description;	
	
	$fields['PAGE']=$page;		
	$fields['LANG']=$lang;
	$fields['URL_OK']=websiteUrl()."/store/epaybg/mode/accept/token/$my_token";
	$fields['URL_CANCEL']=websiteUrl()."/store/epaybg/mode/cancel";		
	
	if (isset($_GET['renew'])){		
		if ($new_info=Yii::app()->functions->getPackagesById($_GET['package_id'])){	   			
			if ($new_info['promo_price']>=1){
				$amount_to_pay=$new_info['promo_price'];
			} else $amount_to_pay=$new_info['price'];
		}	
		$amount_to_pay=number_format($amount_to_pay,2,'.','');			
		$params['AMOUNT']=$amount_to_pay;
	}

    /*dump($min);
	dump($secret);
	dump($params);
	dump($fields);*/
		
	$EpayBg=new EpayBg;		
	$EpayBg->params=$params;
	$EpayBg->fields=$fields;
	$EpayBg->min=$min;
	$EpayBg->secret=$secret;
	$forms=$EpayBg->generateForms();
	
	//save information later get the information
	$trans_type='signup';
	$param1='';
	if (isset($_GET['renew'])){		
		$trans_type="renew";
		$param1=$_GET['package_id'];
	}
	Yii::app()->functions->barclaySaveTransaction($payment_ref,$my_token,$trans_type,$param1);
}
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using EpayBg")?></h1>
          <div class="box-grey rounded">	     
          
            <?php if ( !empty($error)):?>
             <p class="text-danger"><?php echo $error;?></p>  
            <?php else :?>
            <?php echo $forms;?>
            <?php endif;?>
            
             <div class="top25">
			 <a href="<?php echo $back_url?>">
	         <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
	         </div>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->