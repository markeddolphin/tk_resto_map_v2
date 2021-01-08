<?php
$amount_to_pay=0; $error=''; $credentials='';
$payment_description='';
$merchant_name=''; $error = '';
$reference_id=''; $client_info = array();
$trans_type='order';
$currency_code = FunctionsV3::getCurrencyCode();

$back_url = Yii::app()->createUrl('/store/confirmorder');

if ( $data=Yii::app()->functions->getOrder($_GET['id'])){
	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
    $client_id = $data['client_id'];       
    $order_id = $data['order_id']; 	
    $reference_id = $data['order_id_token'];
    
    $merchant_name =isset($data['merchant_name'])?clearString($data['merchant_name']):'';
	$payment_description = Yii::t("default","Payment to merchant [merchant_name]. Order ID#[order]",array(
	  '[merchant_name]'=>$merchant_name,
	  '[order]'=>$_GET['id']
	));
	
	$description = Yii::t("default","Purchase Order ID# [order_id]",array(
	  '[order_id]'=>$_GET['id']
	));
	
	$amount_to_pay = Yii::app()->functions->normalPrettyPrice($data['total_w_tax']);
	$client_email='';
	$client_info=Yii::app()->functions->getClientInfo($client_id);
	
} else $error = t("Sorry but we cannot find what your are looking for.");