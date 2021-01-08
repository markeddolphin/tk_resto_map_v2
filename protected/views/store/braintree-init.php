<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$error='';
$amount_to_pay=0;
$payment_description=Yii::t("default",'Payment to merchant');
$merchant_name='';
$client_token='';
$label=t("Pay");

if(is_array($_POST) && count($_POST)>=1){  
   if($data=Yii::app()->functions->getOrder($getdata['id'])){
	   $client_id=$data['client_id'];
	   $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
	   $amount_to_pay=Yii::app()->functions->prettyFormat($data['total_w_tax'],$merchant_id);
	   $amount_to_pay=unPrettyPrice($amount_to_pay);	   
	   $merchant_type=1;
	   
	   //if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
	   if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
		  $merchant_type=2;
	   }
	   	  
	   $transaction_id=BraintreeClass::PaymentMethod(
	      $merchant_type,
	      $merchant_id,
	      $amount_to_pay,
	      $_POST['payment_method_nonce'],
	      $_SESSION['kr_client']['first_name'],
	      $_SESSION['kr_client']['last_name']
	   );
	   if($transaction_id){
	   	  //echo "<h2>successful</h2>";	   	 
	   	  
	   	  $db_ext=new DbExt;
	        $params_logs=array(
	          'order_id'=>$_GET['id'],
	          'payment_type'=>"btr",
	          'raw_response'=>$transaction_id,
	          'date_created'=>FunctionsV3::dateNow(),
	          'ip_address'=>$_SERVER['REMOTE_ADDR']
	        );
	        $db_ext->insertData("{{payment_order}}",$params_logs);
	        
	        $params_update=array(
	         'status'=>'paid'
	        );	        
	        $db_ext->updateData("{{order}}",$params_update,'order_id',$_GET['id']);
	        
	        /*POINTS PROGRAM*/ 
	        if (FunctionsV3::hasModuleAddon("pointsprogram")){
	           PointsProgram::updatePoints($_GET['id']);
	        }
	        
	        /*Driver app*/
			if (FunctionsV3::hasModuleAddon("driver")){
			   Yii::app()->setImport(array(			
				  'application.modules.driver.components.*',
			   ));
			   Driver::addToTask($_GET['id']);
			}
	        				        
	        $this->redirect( Yii::app()->createUrl('/store/receipt',array(
	          'id'=>$_GET['id']
	        )) );
	        Yii::app()->end();
	   	  
	   } else {
	   	  //echo "<h2>failed</h2>";
	   	  $error=t("Error processing transaction");
	   }
	   
   } else $error=t("Sorry but we cannot find what your are looking for.");	
} else {
   if ( $data=Yii::app()->functions->getOrder($getdata['id'])){	
		if(!empty($data['total_w_tax'])){
			$client_id=$data['client_id'];
			$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
			$amount_to_pay=Yii::app()->functions->prettyFormat($data['total_w_tax'],$merchant_id);
			$amount_to_pay=unPrettyPrice($amount_to_pay);		
			$payment_description.=isset($data['merchant_name'])?$data['merchant_name']:'';
			
			$label.=" ".displayPrice(baseCurrency(),prettyFormat($amount_to_pay,$merchant_id));
			
			$merchant_type=1;
			//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){
			if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
				$merchant_type=2;
			}
			
			/*generate client token*/
			if(!$client_token=BraintreeClass::generateCLientToken($merchant_type,$client_id,$merchant_id)){
				$error=t("Failed generating client token");
			}
			
		} else $error=t("amount to pay is invalid");
	} else $error=t("Sorry but we cannot find what your are looking for.");	
}
?>
<div class="sections section-grey2 section-orangeform">
<div class="container">  
  <div class="row top30">
     <div class="inner">
     <h1><?php echo t("Pay using Braintree")?></h1>
     <div class="box-grey rounded">	
     
     <?php if(!empty($error)):?>
       <p class="text-danger"><?php echo $error?></p>
     <?php else :?>
        <?php if(is_array($_POST) && count($_POST)>=1):?>
           <?php echo t("Payment successful please wait while we redirect you to receipt")?>
        <?php else :?>  
           <?php BraintreeClass::displayForms($client_token, $label)?>
        <?php endif;?>
     <?php endif;?>     
     
      <div class="top25">
       <a href="<?php echo Yii::app()->createUrl('/store/paymentoption')?>">
       <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
      </div>
     
     </div> <!--box-->
     </div> <!--inner-->     
  </div> <!--row-->
</div> <!--container-->
</div> <!--sections-->