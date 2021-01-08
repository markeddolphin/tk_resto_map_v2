<?php
$db_ext=new DbExt;
$error='';
$my_token=isset($_GET['token'])?$_GET['token']:'';
$package_id=isset($_GET['package_id'])?$_GET['package_id']:'';	
$is_merchant=true;

$amount_to_pay=0;
$payment_description=Yii::t("default",'Payment to merchant');

$order_id=isset($_GET['id'])?$_GET['id']:'';

$back_url=Yii::app()->request->baseUrl."/store/PaymentOption";
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{order}}');

if ( $data=Yii::app()->functions->getOrder($_GET['id'])){		
	
	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
	$payment_description.=isset($data['merchant_name'])?" ".$data['merchant_name']:'';	
	
	$amount_to_pay=isset($data['total_w_tax'])?Yii::app()->functions->standardPrettyFormat($data['total_w_tax']):'';
	
	
    $cancel_url=Yii::app()->getBaseUrl(true)."/store/PaymentOption";	
	$accepturl=Yii::app()->getBaseUrl(true)."/store/payserainit/id/$order_id/mode/accept";	
	$callback=Yii::app()->getBaseUrl(true)."/store/payserainit/id/$order_id/mode/callback";
	
	$country=Yii::app()->functions->getOption('merchant_paysera_country',$merchant_id);
    $mode=Yii::app()->functions->getOption('merchant_paysera_mode',$merchant_id);
    $lang=Yii::app()->functions->getOption('merchant_paysera_lang',$merchant_id);
    $currency=Yii::app()->functions->adminCurrencyCode();	  
    $projectid=Yii::app()->functions->getOption('merchant_paysera_project_id',$merchant_id);		  
    $password=Yii::app()->functions->getOption('merchant_paysera_password',$merchant_id);
    
    /*COMMISSION*/
	//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
		$country=Yii::app()->functions->getOptionAdmin('admin_paysera_country');
	    $mode=Yii::app()->functions->getOptionAdmin('admin_paysera_mode');
	    $lang=Yii::app()->functions->getOptionAdmin('admin_paysera_lang');
	    $currency=Yii::app()->functions->adminCurrencyCode();	  
	    $projectid=Yii::app()->functions->getOptionAdmin('admin_paysera_project_id');		  
	    $password=Yii::app()->functions->getOptionAdmin('admin_paysera_password');
	}
	    
    if (isset($_GET['mode'])){    	
	    $response = WebToPay::checkResponse($_GET, array(
          'projectid'     => $projectid,
          'sign_password' => $password,
        ));        
        if (is_array($response) && count($response)>=1){          	
        	if ($response['status']==0){
        		$error=t("payment has no been executed");
        	}
        	if ($response['status']==3){
        		$error=t("additional payment information");
        	}
        	if (empty($error)){        		
        		$db_ext=new DbExt;
		        $params_logs=array(
		          'order_id'=>$_GET['id'],
		          'payment_type'=>"pys",
		          'raw_response'=>json_encode($response),
		          'date_created'=>FunctionsV3::dateNow(),
		          'ip_address'=>$_SERVER['REMOTE_ADDR'],
		          'payment_reference'=>$response['orderid']
		        );		        
		        $db_ext->insertData("{{payment_order}}",$params_logs);		      

		        $params_update=array('status'=>'paid');	        
	            $db_ext->updateData("{{order}}",$params_update,'order_id',$_GET['id']);
		          		        
		        $this->redirect( Yii::app()->createUrl('/store/receipt',array(
		          'id'=>$_GET['id']
		        )) );
        		die();
        	}        	
        } else $error=t("ERROR: api returns empty");
        	    	  
    } else {
    	try {						  
		  $params_request=array(
	        'projectid'     => $projectid,
	        'sign_password' => $password,
	        'orderid'       => $payment_ref,
	        'amount'        => $amount_to_pay*100,
	        'currency'      => $currency,
	        'country'       => $country,
	        'accepturl'     => $accepturl,
	        'cancelurl'     => $cancel_url,
	        'callbackurl'   => $callback,
	        'test'          => $mode,
	        'lang'          =>$lang
	       );	
	       if ($mode==2){
	       	   unset($params_request['test']);
	       }       
	       //dump($params_request);	      
		   $request = WebToPay::redirectToPayment($params_request);
			
		} catch (WebToPayException $e) {
	       $error=t("ERROR: Something went wrong").". ".$e;
	    }    
    }	
} else $error=Yii::t("default","Failed. Cannot process payment");  
?>
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  <?php else :?>

  <?php echo t("Please wait while we redirect you")?>
  
  <?php endif;?>      
  <div style="height:10px;"></div>
  <a href="<?php echo $back_url;?>"><?php echo Yii::t("default","Go back")?></a>
  
  </div>
</div>