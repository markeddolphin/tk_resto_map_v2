<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$data='';
$data2='';
$params=array();
$error='';
$merchant_id='';
$ok=false;

if ( $data=Yii::app()->functions->getOrder($_GET['id'])){
	$merchant_id=$data['merchant_id'];	
	$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
	//dump($json_details);
		
	if ( $json_details !=false){
		$p_arams=array( 
		   'merchant_id'=>$data['merchant_id'],
		   'delivery_type'=>$data['trans_type'],
		   'voucher_amount'=>$data['voucher_amount'],
		   'voucher_type'=>$data['voucher_type']
		);		
		Yii::app()->functions->displayOrderHTML($p_arams,$json_details,true);
		if ( Yii::app()->functions->code==1){
			$ok=true;
		}
	}	
}

if ( $ok==TRUE){
   $data2=Yii::app()->functions->details['raw'];        
   $paypal_con=Yii::app()->functions->getPaypalConnection($merchant_id);  
   
   /*get admin paypal connection if merchant is commission*/
   //if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
   if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
   	   unset($paypal_con);   	   
   	   $paypal_con=Yii::app()->functions->getPaypalConnectionAdmin();   	   
   }      
   
   //dump($paypal_con);die();
   
   if ( !empty($paypal_con[$paypal_con['mode']]['user'])){   	     
	   if (is_array($data) && count($data)>=1){
	   	   $x=0;	   	   	   	   
	       
	   	   //dump($data);
	   	   
	       $params['L_NAME'.$x]= t("Payment to merchant") ." ". stripslashes($data['merchant_name']) ;
           $params['L_NUMBER'.$x]= $data['order_id'];
           $params['L_DESC'.$x]='';
           $params['L_AMT'.$x]= normalPrettyPrice($data['total_w_tax']);
           $params['L_QTY'.$x]=1;
                      
           $params['AMT']=isIsset(  normalPrettyPrice($data['total_w_tax']) );
           
           $params['RETURNURL']=websiteUrl()."/paypalverify";
		   $params['CANCELURL']=websiteUrl()."/paymentoption";	  	  
		   
		   $params['NOSHIPPING']='1';
	       $params['LANDINGPAGE']='Billing';
	       $params['SOLUTIONTYPE']='Sole';
	       $params['CURRENCYCODE']=Yii::app()->functions->adminCurrencyCode();
	          
	       /*dump($params);
	       die();*/
	          	   
	   	   $paypal=new Paypal($paypal_con);
	  	   $paypal->params=$params;
	  	   $paypal->debug=false;
	  	   if ($resp=$paypal->setExpressCheckout()){  	   	  
	  	  	  $insert['token']=$resp['token'];
	  	  	  $insert['order_id']=isIsset($_GET['id']);
	  	  	  $insert['date_created']=FunctionsV3::dateNow();
	  	  	  $insert['ip_address']=$_SERVER['REMOTE_PORT'];	  	  	  
	  	  	  $insert['paypal_request']=json_encode($paypal->params);
	  	  	  $insert['paypal_response']=json_encode($resp['resp']);	
	  	  	  Yii::app()->functions->paypalSavedToken($insert);	  	  	  
	  	  	  header('Location: '.$resp['url']);
	  	   } else {
	  	  	 $error=$paypal->getError();
	  	   }
	   }
   } else $error=Yii::t("default","Merchant Paypal Credential not yet been set.");
}
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using Paypal")?></h1>
          <div class="box-grey rounded">	
          
          <?php if ( !empty($error)):?>
           <p class="text-danger"><?php echo $error;?></p>  
          <?php else :?> 
           <p><?php echo t("Please wait while we redirect you to paypal.")?></p>
          <?php endif;?>
               
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->
