<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Payment"),
   'sub_text'=>t("")
));

$this->renderPartial('/front/order-progress-bar',array(
   'step'=>4,
   'show_bar'=>true
));

$back_url=Yii::app()->request->baseUrl."/store/PaymentOption";
$amount_to_pay=0;
$order_id=isset($_GET['id'])?$_GET['id']:'';
if ( $data=Yii::app()->functions->getOrder($order_id)){	
	
	$payment_description='';
	$merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';	
	$payment_description.=isset($data['merchant_name'])?" ".$data['merchant_name']:'';		
	$amount_to_pay=isset($data['total_w_tax'])?Yii::app()->functions->standardPrettyFormat($data['total_w_tax']):'';
	
	$payment_ref=Yii::app()->functions->generateCode()."TT".$order_id;
	$payment_description=Yii::t("default",'Payment for Food Order -');
	
	$db_ext=new DbExt;
	$payment_code=Yii::app()->functions->paymentCode("barclay");
	$error='';
	
	$merchant_barclay_mode=Yii::app()->functions->getOption('merchant_mode_barclay',$merchant_id);	
	if ($merchant_barclay_mode=="sandbox"){
		$pspid=Yii::app()->functions->getOption('merchant_sandbox_barclay_pspid',$merchant_id);
		$psp_pass=Yii::app()->functions->getOption('merchant_sandbox_barclay_password',$merchant_id);
	} else {
		$pspid=Yii::app()->functions->getOption('merchant_live_barclay_pspid',$merchant_id);
		$psp_pass=Yii::app()->functions->getOption('merchant_live_barclay_password',$merchant_id);
	}	
		
	$params['AMOUNT']=($amount_to_pay*100);	
	$params['BGCOLOR']=Yii::app()->functions->getOption('merchant_bcy_bgcolor',$merchant_id);
	$params['BUTTONBGCOLOR']=Yii::app()->functions->getOption('merchant_bcy_buttoncolor',$merchant_id);
	$params['BUTTONTXTCOLOR']=Yii::app()->functions->getOption('merchant_bcy_buttontextcolor',$merchant_id);
	$params['COM']=$payment_description;
	$params['CURRENCY']=Yii::app()->functions->getOption('merchant_bcy_currency',$merchant_id);
	$params['FONTTYPE']=Yii::app()->functions->getOption('merchant_bcy_font',$merchant_id);
	$params['LANGUAGE']=Yii::app()->functions->getOption('merchant_bcy_language',$merchant_id);   
    $params['LOGO']=Yii::app()->functions->getOption('merchant_bcy_logo',$merchant_id);
	$params['ORDERID']=$payment_ref;	
	$params['PMLISTTYPE']=Yii::app()->functions->getOption('merchant_bcy_listype',$merchant_id);	
	$params['PSPID']=$pspid;			
	$params['TBLBGCOLOR']=Yii::app()->functions->getOption('merchant_bcy_table_bgcolor',$merchant_id);	
	$params['TBLTXTCOLOR']=Yii::app()->functions->getOption('merchant_bcy_table_textcolor',$merchant_id);	
	$params['TITLE']=Yii::app()->functions->getOption('merchant_bcy_title',$merchant_id);
	$params['TXTCOLOR']=Yii::app()->functions->getOption('merchant_bcy_textcolor',$merchant_id);	
	//$params['CATALOGURL']='http://localhost/restomulti/store/bcyinit/id/3';
	
	
	/*COMMISSION*/
	//if ( Yii::app()->functions->isMerchantCommission($merchant_id)){	
	if (FunctionsV3::isMerchantPaymentToUseAdmin($merchant_id)){
		$admin_barclay_mode=Yii::app()->functions->getOptionAdmin('admin_mode_barclay');	
		if ($admin_barclay_mode=="sandbox"){
			$pspid=Yii::app()->functions->getOptionAdmin('admin_sandbox_barclay_pspid');
			$psp_pass=Yii::app()->functions->getOptionAdmin('admin_sandbox_barclay_password');
		} else {
			$pspid=Yii::app()->functions->getOptionAdmin('admin_live_barclay_pspid');
			$psp_pass=Yii::app()->functions->getOptionAdmin('admin_live_barclay_password');
		}

		$params['BGCOLOR']=Yii::app()->functions->getOptionAdmin('admin_bcy_bgcolor');
		$params['BUTTONBGCOLOR']=Yii::app()->functions->getOptionAdmin('admin_bcy_buttoncolor');
		$params['BUTTONTXTCOLOR']=Yii::app()->functions->getOptionAdmin('admin_bcy_buttontextcolor');
		$params['COM']=$payment_description;
		$params['CURRENCY']=Yii::app()->functions->getOptionAdmin('admin_bcy_currency');
		$params['FONTTYPE']=Yii::app()->functions->getOptionAdmin('admin_bcy_font');
		$params['LANGUAGE']=Yii::app()->functions->getOptionAdmin('admin_bcy_language');   
	    $params['LOGO']=Yii::app()->functions->getOptionAdmin('admin_bcy_logo');
		$params['ORDERID']=$payment_ref;	
		$params['PMLISTTYPE']=Yii::app()->functions->getOptionAdmin('admin_bcy_listype');	
		$params['PSPID']=$pspid;			
		$params['TBLBGCOLOR']=Yii::app()->functions->getOptionAdmin('admin_bcy_table_bgcolor');	
		$params['TBLTXTCOLOR']=Yii::app()->functions->getOptionAdmin('admin_bcy_table_textcolor');	
		$params['TITLE']=Yii::app()->functions->getOptionAdmin('admin_bcy_title');
		$params['TXTCOLOR']=Yii::app()->functions->getOptionAdmin('admin_bcy_textcolor');
				
	}
	
	if (!empty($pspid) && !empty($psp_pass)){
		
		$Barclay=new Barclay;
		$Barclay->params=$params;
		$Barclay->sha_password=$psp_pass;
		$Barclay->mode=$merchant_barclay_mode;
		$forms=$Barclay->generateForms();
	
		/*save information to get after the payment*/
		Yii::app()->functions->barclaySaveTransaction($payment_ref,$order_id,"order");
	} else $error=t("This merchant has not properly setup payment gateway");
	
} else $error=t("Something went wrong during processing your request. Please try again later.");
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
    <div class="row top30">
       <div class="inner">
          <h1><?php echo t("Pay using Barclay")?></h1>
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