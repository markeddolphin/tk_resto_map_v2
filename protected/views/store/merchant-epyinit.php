<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$payment_description='';
$back_url=Yii::app()->request->baseUrl."/store/PaymentOption";
$amount_to_pay=0;
$order_id=isset($_GET['id'])?$_GET['id']:'';
if ( $data=Yii::app()->functions->getOrder($order_id)){	
	
	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
	$payment_description.=isset($data['merchant_name'])?" ".$data['merchant_name']:'';		
	$amount_to_pay=isset($data['total_w_tax'])?Yii::app()->functions->standardPrettyFormat($data['total_w_tax']):'';
	
	$payment_ref=Yii::app()->functions->generateCode()."TT".$order_id;
	$payment_description=Yii::t("default",'Payment for Food Order -');
	$payment_description.=isset($data['merchant_name'])?" ".$data['merchant_name']:'';	
	
	$db_ext=new DbExt;
	$payment_code=Yii::app()->functions->paymentCode("barclay");
	$error='';
		
	$mode=Yii::app()->functions->getOption('merchant_mode_epaybg',$merchant_id);	
	if ($mode=="sandbox"){
		$params['mode']='sandbox';
		$min=Yii::app()->functions->getOption('merchant_sandbox_epaybg_min',$merchant_id);
		$secret=Yii::app()->functions->getOption('merchant_sandbox_epaybg_secret',$merchant_id);
		$page=Yii::app()->functions->getOption('merchant_sandbox_epaybg_request',$merchant_id);
		$lang=Yii::app()->functions->getOption('merchant_sandbox_epaybg_lang',$merchant_id);
	} else {
		$params['mode']='live';
		$min=Yii::app()->functions->getOption('merchant_live_epaybg_min',$merchant_id);
		$secret=Yii::app()->functions->getOption('merchant_live_epaybg_secret',$merchant_id);
		$page=Yii::app()->functions->getOption('merchant_live_epaybg_request',$merchant_id);
		$lang=Yii::app()->functions->getOption('merchant_live_epaybg_lang',$merchant_id);
	}		
	
	/*COMMISSION*/
	//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){	
	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
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
	}	
		
	$amount_to_pay=number_format($amount_to_pay,2,'.','');	
				
	$params['MIN']=$min;
	$params['INVOICE']=$payment_ref;
	$params['AMOUNT']=$amount_to_pay;
	$params['CURRENCY']=adminCurrencyCode();
	$params['EXP_TIME']=date('d.m.Y', strtotime ('+5 days'));
	$params['DESCR']=$payment_description;	
	
	$fields['PAGE']=$page;		
	$fields['LANG']=$lang;
	$fields['URL_OK']=websiteUrl()."/store/epaybg/mode/accept/token/$order_id";
	$fields['URL_CANCEL']=websiteUrl()."/store/epaybg/mode/cancel";		
	
	/*dump($min);
	dump($secret);
	dump($params);
	dump($fields);*/
	
	if (!empty($min) && !empty($secret)){
		$EpayBg=new EpayBg;		
		$EpayBg->params=$params;
		$EpayBg->fields=$fields;
		$EpayBg->min=$min;
		$EpayBg->secret=$secret;
		$forms=$EpayBg->generateForms();
		
		//save information later get the information
		$trans_type='order';
		
		$param3=$EpayBg->getEncoded();
		Yii::app()->functions->barclaySaveTransaction($payment_ref,$order_id,$trans_type,'',$merchant_id,$param3);
	} else $error=t("This merchant has not properly setup payment gateway");
	
} else $error=t("Something went wrong during processing your request. Please try again later.");
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
		     <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
             <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
            </div>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->