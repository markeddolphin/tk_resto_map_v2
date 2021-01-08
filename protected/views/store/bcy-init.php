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
$payment_description=Yii::t("default",'Payment to merchant');

$back_url=Yii::app()->request->baseUrl."/store/merchantSignup/Do/step3/token/$my_token";
$payment_ref=Yii::app()->functions->generateCode()."TT".Yii::app()->functions->getLastIncrement('{{order}}');

$forms='';

if ( $res=Yii::app()->functions->getMerchantByToken($my_token)){ 		
	
	$admin_barclay_mode=Yii::app()->functions->getOptionAdmin('admin_mode_barclay');	
	if ($admin_barclay_mode=="sandbox"){
		$pspid=Yii::app()->functions->getOptionAdmin('admin_sandbox_barclay_pspid');
		$psp_pass=Yii::app()->functions->getOptionAdmin('admin_sandbox_barclay_password');
	} else {
		$pspid=Yii::app()->functions->getOptionAdmin('admin_live_barclay_pspid');
		$psp_pass=Yii::app()->functions->getOptionAdmin('admin_live_barclay_password');
	}
	
	$amount_to_pay=normalPrettyPrice($res['package_price']);	
	$amount_to_pay=number_format($amount_to_pay,2,'.','');	
			
	$params['AMOUNT']=($amount_to_pay*100);
	
	if (isset($_GET['renew'])){		
		if ($new_info=Yii::app()->functions->getPackagesById($_GET['package_id'])){	   			
			if ($new_info['promo_price']>=1){
				$amount_to_pay=$new_info['promo_price'];
			} else $amount_to_pay=$new_info['price'];
		}	
		$amount_to_pay=number_format($amount_to_pay,2,'.','');			
		$params['AMOUNT']=($amount_to_pay*100);
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
		
	$Barclay=new Barclay;
	$Barclay->params=$params;
	$Barclay->sha_password=$psp_pass;
	$Barclay->mode=$admin_barclay_mode;
	$forms=$Barclay->generateForms();
	
	/*dump($params);
	die();*/
	//save information later get the information
	$trans_type='signup';
	if (isset($_GET['renew'])){		
		$trans_type="renew";
	}	
	Yii::app()->functions->barclaySaveTransaction($payment_ref,$my_token,$trans_type);
}

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
		   <a href="<?php 
		   if (isset($_GET['renew'])) {		 
			    	echo Yii::app()->createUrl('store/merchantsignup',array(
				     'do'=>"step3",
				     'token'=>$my_token,
				     'renew'=>1,
				     'package_id'=>$package_id
				   ));
			    } else {
	 			    echo Yii::app()->createUrl('store/merchantsignup',array(
				      'do'=>"step3",
				      'token'=>$my_token));
			    }
		   ?>">
           <i class="ion-ios-arrow-thin-left"></i> <?php echo Yii::t("default","Click here to change payment option")?></a>
          </div>
          
          </div> <!--box-->
       </div> <!--inner-->
    </div> <!--row-->
  </div> <!--container-->
</div><!-- sections-->
