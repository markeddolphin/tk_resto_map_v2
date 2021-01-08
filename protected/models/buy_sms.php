<?php
$error=''; $package_id=''; $reference_id=''; $amount_to_pay=0;
$description=''; $payment_description='';

$trans_type='sms';
$currency_code = FunctionsV3::getCurrencyCode();
$merchant_id=Yii::app()->functions->getMerchantID();

if($merchant_id>0){
	$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';
	if($package_id>0){
		if ( $data=Yii::app()->functions->getSMSPackagesById($package_id) ){
			
			$reference_id=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{sms_package_trans}}');		
			
			$payment_description= Yii::t("default","SMS purchase [title]",array(
			  '[title]'=>$data['title']
			));
			
			$amount_to_pay=$data['price'];
			if ( $data['promo_price']>0){
				$amount_to_pay=$data['promo_price'];
			}	  				
			$amount_to_pay = Yii::app()->functions->normalPrettyPrice($amount_to_pay);
			
			$description = Yii::t("default","SMS purchase reference# [reference_id]",array(
			  '[reference_id]'=>$reference_id
			));
			
		} else $error = t("invalid package id");
	} else $error = t("invalid package id");
} else $error =t("Error session has expired");