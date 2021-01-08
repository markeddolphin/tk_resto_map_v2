<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$db_ext=new DbExt;
$error='';
$success='';
$amount_to_pay=0;
$payment_description=Yii::t("default",'Payment to merchant');
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{order}}');
$data_get=$_GET;

if ( $data=Yii::app()->functions->getOrder($_GET['id'])){	
	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';
	//dump($merchant_id);
	$payment_description.=isset($data['merchant_name'])?" ".$data['merchant_name']:'';	
		
    $mtid=Yii::app()->functions->getOption('merchant_sanbox_sisow_secret_key',$merchant_id);
    $mtkey=Yii::app()->functions->getOption('merchant_sandbox_sisow_pub_key',$merchant_id);
    $mtshopid=Yii::app()->functions->getOption('merchant_sandbox_sisow_shopid',$merchant_id);
    $mode=Yii::app()->functions->getOption('merchant_sisow_mode',$merchant_id);
    
    /*COMMISSION*/
	//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){	
	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
		$mtid=Yii::app()->functions->getOptionAdmin('admin_sanbox_sisow_secret_key');
        $mtkey=Yii::app()->functions->getOptionAdmin('admin_sandbox_sisow_pub_key');
        $mtshopid=Yii::app()->functions->getOptionAdmin('admin_sandbox_sisow_shopid');
        $mode=Yii::app()->functions->getOptionAdmin('admin_sisow_mode');
	}
    
    $amount_to_pay=isset($data['total_w_tax'])?Yii::app()->functions->standardPrettyFormat($data['total_w_tax']):'';
    $amount_to_pay=is_numeric($amount_to_pay)?unPrettyPrice($amount_to_pay):'';
        
    if ( empty($mtid) || empty($mtkey)){
		$error=Yii::t("default","This payment method is not properly configured");
	} else {
		$sisow = new Sisow($mtid, $mtkey,$mtshopid);
	}
    
	if (empty($error)){
		if (isset($_POST["issuerid"])) {
			
			$data_post=$_POST;							
			$return_url=Yii::app()->getBaseUrl(true)."/store/sisowinit/id/".$data_get['id'];
											
			$sisow->purchaseId = $payment_ref;
			$sisow->description = $payment_description;
			$sisow->amount = $amount_to_pay;
			$sisow->payment = $data_post['payment_method'];
			$sisow->issuerId = $data_post["issuerid"];
			$sisow->returnUrl = $return_url;
			$sisow->notifyUrl = $sisow->returnUrl;			
			
			if (($ex = $sisow->TransactionRequest()) < 0) {				
				$error=$sisow->errorCode." ".$sisow->errorMessage;
			} else header("Location: " . $sisow->issuerUrl);			
			
		} else if (isset($_GET["trxid"])) {
						
			if ($data_get['status']=="Success"){				
				$params_logs=array(
		          'order_id'=>$data_get['id'],
		          'payment_type'=>Yii::app()->functions->paymentCode('sisow'),
		          'raw_response'=>json_encode($data_get),
		          'date_created'=>FunctionsV3::dateNow(),
		          'ip_address'=>$_SERVER['REMOTE_ADDR'],
		          'payment_reference'=>$data_get['trxid']
		        );
		        $db_ext->insertData("{{payment_order}}",$params_logs);

		        	        
		        $params_update=array('status'=>'paid');	        
	            $db_ext->updateData("{{order}}",$params_update,'order_id',$data_get['id']);
		        		        
		        $this->redirect( Yii::app()->createUrl('/store/receipt',array(
		          'id'=>$_GET['id']
		        )) );
		        die();
			} else $error=Yii::t("default","Payment Failed"." ".$data_get['status']);	
		} else {		
			$testmode = $mode=="Sandbox"?true:false;
			$sisow->DirectoryRequest($select, true, $testmode);	
		}
	}	
} else  $error=Yii::t("default","Sorry but we cannot find what your are looking for.");	
?>


<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using Sisow")?></h1>
          <div class="box-grey rounded">	
          
          
            <?php if ( !empty($error)):?>
		       <p class="text-danger"><?php echo $error;?></p>  		  
		    <?php else :?>
		    
		       
		       <?php echo CHtml::beginForm( '',
			    'post', 
			    array('class'=>'uk-form uk-form-horizontal forms') 
			   );
			   ?>
		       
               <input type="hidden" id="action" name="action" value="sisowPaymentMerchant">
               
	               <?php echo CHtml::hiddenField('payment_ref',
				  $payment_ref
				  ,array(
				  'class'=>'uk-form-width-large'  
				  ))?>  
				  <?php echo CHtml::hiddenField('description',
				  $payment_description
				  ,array(
				  'class'=>'grey-fields full-width'  
				  ))?>
			
				<div class="row top10">
				  <div class="col-md-3"><?php echo t("Amount")?></div>
				  <div class="col-md-8">
				   <?php echo CHtml::textField('amount',
				  $amount_to_pay
				  ,array(
				  'class'=>'grey-fields full-width',
				  'disabled'=>true
				  ))?>
				  </div>
				</div>
				
	           <div class="row top10">
				  <div class="col-md-3"><?php echo t("Payment Method")?></div>
				  <div class="col-md-8">
				  <select name="payment_method" class="grey-fields full-width" id="payment_method" >
				    <option value="">iDEAL</option>
				    <option value="sofort">DIRECTebanking</option>
				    <option value="mistercash">MisterCash</option>
				    <option value="webshop">WebShop GiftCard</option>
				    <option value="podium">Podium Cadeaukaart</option>
				  </select>
				  </div>
				</div>
				
				
	           <div class="row top10">
				  <div class="col-md-3"><?php echo t("Bank")?></div>
				  <div class="col-md-8">
				   <?php echo $select;?>
				  </div>
				</div>
				
		       <div class="row top10">
				  <div class="col-md-3"></div>
				  <div class="col-md-8">
				  <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="black-button inline medium">
				  </div>
				</div>	
							  	                         
               <?php echo CHtml::endForm() ; ?> 
  
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
