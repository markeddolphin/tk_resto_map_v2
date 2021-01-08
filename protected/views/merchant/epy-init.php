<?php
$merchant_name=Yii::app()->functions->getOptionAdmin('website_title');
 if (empty($name)){		 	
 	$merchant_name=Yii::app()->name;
 }
		 
$step2=false;
$amount_to_pay=0;
$payment_description='';

$secret_key='';
$publishable_key='';

$transaction_type=isset($_GET['type'])?$_GET['type']:'';

$payment_code=Yii::app()->functions->paymentCode("stripe");
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
$my_token=md5(Yii::app()->functions->generateCode());

if ( $res=Yii::app()->functions->getSMSPackagesById($package_id) ){
		
	$payment_ref=Yii::app()->functions->generateCode().
	"TT".Yii::app()->functions->getLastIncrement('{{barclay_trans}}');
	
	
	$amount_to_pay=$res['price'];
	if ( $res['promo_price']>0){
		$amount_to_pay=$res['promo_price'];
	}	    
		
	$amount_to_pay=normalPrettyPrice($amount_to_pay);	
	$amount_to_pay=number_format($amount_to_pay,2,'.','');	
	
	$payment_description.=isset($res['title'])?$res['title']:'';	
	/*dump($amount_to_pay);
	dump($payment_description);*/

	$mode=Yii::app()->functions->getOptionAdmin('admin_mode_epaybg');	
	$mode=strtolower($mode);	
	
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
	
	$params['MIN']=$min;
	$params['INVOICE']=$payment_ref;
	$params['AMOUNT']=$amount_to_pay;
	$params['CURRENCY']=adminCurrencyCode();
	$params['EXP_TIME']=date('d.m.Y', strtotime ('+5 days'));
	$params['DESCR']=$payment_description;	
	
	$fields['PAGE']=$page;		
	$fields['LANG']=$lang;
	$fields['URL_OK']=websiteUrl()."/merchant/epaybg/mode/accept/token/$my_token";
	$fields['URL_CANCEL']=websiteUrl()."/merchant/epaybg/mode/cancel";		
		
    $params['AMOUNT']=$amount_to_pay;
	
    
    $EpayBg=new EpayBg;		
	$EpayBg->params=$params;
	$EpayBg->fields=$fields;
	$EpayBg->min=$min;
	$EpayBg->secret=$secret;
	$forms=$EpayBg->generateForms();
    
    /*dump($params);
	dump($fields);*/
	
	$trans_type="sms_purchase";
	Yii::app()->functions->barclaySaveTransaction($payment_ref,$my_token,$trans_type,$package_id);
	
} else $error=Yii::t("default","Sorry but we cannot find what your are looking for.");
?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
  <h3><?php echo t("Amount")." ".displayPrice(adminCurrencySymbol(),standardPrettyFormat($amount_to_pay));?></h3>
    
  <?php echo $forms;?>
  
  <?php endif;?>      
  
  </div>
</div>